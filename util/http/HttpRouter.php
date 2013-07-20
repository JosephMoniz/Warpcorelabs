<?php
namespace util\http;
use Exception;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\Map;
use PlasmaConduit\headers\AbstractHttpRequestHeader;
use PlasmaConduit\headers\request\HostHeader;
use PlasmaConduit\option\None;
use PlasmaConduit\option\Option;
use PlasmaConduit\option\Some;
use PlasmaConduit\ServiceManager;
use util\http\router\AbstractRouteHandler;

class HttpRouter {

    /**
     * The array configuration of routes to match against
     *
     * @var array
     */
    private $_routes;

    /**
     * The fallback route to use when none matched
     *
     * @var AbstractRouteHandler
     */
    private $_default;

    /**
     * A reference to the http request
     *
     * @var HttpRequest
     */
    private $_request;

    /**
     * The last matched route so we can resume routing later
     * if we ever find a need o do so
     *
     * @var \PlasmaConduit\option\None
     */
    private $_previous;

    public function __construct(array $routes, AbstractRouteHandler $fallback) {
        $this->_routes   = Map::mappify($routes);
        $this->_default  = $fallback;
        $this->_previous = new None();
    }

    /**
     * Routes the current request
     *
     * @param HttpRequest $request          - The request to match against
     * @return \PlasmaConduit\option\Option - The matched route if any
     * @throws Exception
     */
    public function route(HttpRequest $request) {
        if ($this->_previous->nonEmpty()) {
            throw new Exception("You can only call route once.");
        }

        return $this->_routingFlow($request, $this->_previous);
    }

    /**
     * Resumes looking fort he next matching route from where it left off
     * when route() was called.
     *
     * @return \PlasmaConduit\option\Option - The next matched route if any
     * @throws \Exception
     */
    public function next() {
        if ($this->_previous->isEmpty()) {
            throw new Exception(
                "You can't call next if route wasn't called with a match"
            );
        }

        return $this->_routingFlow($this->_request, $this->_previous);
    }

    /**
     * @param HttpRequest $request
     * @param Option $previous
     * @return Option
     */
    private function _routingFlow(HttpRequest $request, Option $previous) {
        $methods = $this->_getMethodsByHost($request);
        $routes  = $this->_getRoutesByMethod($request, $methods);
        $route   = $this->_getNextMatchingRoute($request, $previous, $routes);

        $this->_request  = $request;
        $this->_previous = $route;

        return $route;
    }

    /**
     * @param HttpRequest $request
     * @return Option
     */
    private function _getMethodsByHost(HttpRequest $request) {
        return $request->getHeader(new HostHeader())
                       ->flatMap(function(AbstractHttpRequestHeader $host)
        {
            return $this->_findCriteriaMatch(
                $host->getValue(),
                new Some($this->_routes)
            );
        });

    }

    /**
     * @param HttpRequest $request
     * @param Option $methods
     * @return Option
     */
    private function _getRoutesByMethod(HttpRequest $request, Option $methods) {
        return $methods->flatMap(function(Map $methods) use($request) {
            return $methods->get($request->getMethod());
        });
    }

    /**
     * Gets the next matching route from a supplied list of routes
     *
     * @param HttpRequest $request
     * @param Option $previous
     * @param Option $routes
     * @return Option
     */
    private function _getNextMatchingRoute(HttpRequest $request,
                                           Option $previous,
                                           Option $routes)
    {
        list($uri) = explode("?", $request->getPath());
        $remaining = $this->_getRemainingRoutes($previous, $routes);
        $matched   = $this->_findCriteriaMatch($uri, $remaining);
        return $this->_matchedOrElseDefault($matched);
    }

    /**
     * @param Option $previous
     * @param Option $routes
     * @return Option
     */
    private function _getRemainingRoutes(Option $previous, Option $routes) {
        return $routes->map(function(Map $routes) use($previous) {
            return $previous->map(function($previous) use($routes) {
                $f     = false;
                $init  = new Map();
                $routes->reduce($init, function($v, $k) use(&$f, $previous) {
                    if ($f) {
                        return true;
                    } else {
                        if ($v == $previous) {
                            $f = true;
                        }
                        return false;
                    }
                });
            })->getOrElse($routes);
        });
    }

    /**
     * @param Option $matched
     * @return Option
     */
    private function _matchedOrElseDefault(Option $matched) {
        return $matched->orElse(function() {
            if ($this->_default) {
                return new Some($this->_default);
            } else {
                return new None();
            }
        });
    }

    /**
     * Helper method for determining if a string is a regex pattern
     *
     * @param string $string - The string to check
     * @return Boolean       - True if regex pa    tern false otherwise
     */
    private function _isRegexPattern($string) {
        return (strpos($string, "r:") === 0);
    }

    /**
     * Helper method for checking if a string is a regex match
     *
     * @param string $pattern - The pattern to base the match off
     * @param string $subject - The subject to perform the match on
     * @return Boolean        - True on ma    ch false otherwise
     */
    private function _isRegexMatch($pattern, $subject) {
        $key = substr($pattern, 2);
        return preg_match($key, $subject) === 1;
    }

    /**
     * Helper method for finding a matching key based on the criteria
     * int he supplied subject
     *
     * @param $criteria
     * @param Option $routes
     * @return Option
     */
    private function _findCriteriaMatch($criteria, Option $routes) {
        return $routes->flatMap(function(Map $subject) use($criteria) {
            $match = $subject->get($criteria);
            return $this->_orElseRegexMatch($match, $criteria, $subject);
        });
    }

    /**
     * @param Option $match
     * @param $criteria
     * @param Map $routes
     * @return Option
     */
    private function _orElseRegexMatch(Option $match, $criteria, Map $routes) {
        return $match->orElse(function() use($criteria, $routes) {
            return $routes->findValue(function($value, $key) use($criteria) {
                if ($this->_isRegexPattern($key)) {
                    return $this->_isRegexMatch($key, $criteria);
                } else {
                    return false;
                }
            });
        });
    }

}
