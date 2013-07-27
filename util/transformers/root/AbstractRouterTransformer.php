<?php
namespace util\transformers\root;
use PlasmaConduit\AbstractHttpRouteHandler;
use PlasmaConduit\Map;
use PlasmaConduit\option\Option;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\HttpRouter;
use PlasmaConduit\responses\MissingResponse;
use PlasmaConduit\views\StringView;

abstract class AbstractRouterTransformer extends AbstractTransformer {

    /**
     * This transformer finds the first matching route, runs it and
     * passes it's result through the rest of the pipeline
     *
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        /** @var \PlasmaConduit\ServiceManager $sm  */
        $router  = new HttpRouter($this->routes(), $this->fallback());
        $sm      = $subject->get("serviceManager")->get();
        $request = $sm->get("request");
        $route   = $this->_getRoute($router, $request);

        $subject->set("router", $router);

        return $this->_getResult($route, $subject);
    }

    /**
     * Given a router and an optional request, this method returns an
     * optional route if a matching one was found and the request
     * wasn't empty. The request should never be empty, but we take
     * precautions just in case.
     *
     * @param HttpRouter $router
     * @param Option $request
     * @return Option
     */
    private function _getRoute(HttpRouter $router, Option $request) {
        return $request->flatMap(function($request) use($router) {
            return $router->route($request);
        });
    }

    /**
     * Given an optional route and the subject we apply the subject to
     *t he route and return a new optional subject to hand to the
     * rest of the pipeline
     *
     * @param Option $route
     * @param Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    private function _getResult(Option $route, Map $subject) {
        $result = $this->_handleRouteIfValid($route, $subject);
        return $this->_fallbackToMissing($result);
    }

    /**
     * Given a path to a route handler, return an initialized route handler
     *
     * @param string $path
     * @return AbstractHttpRouteHandler
     */
    private function _pathToRoute($path) {
        return new $path();
    }

    /**
     * Given an optional route and a subject, handle the route if one exists
     *
     * @param Option $route
     * @param Map $subject
     * @return Option
     */
    private function _handleRouteIfValid(Option $route, Map $subject) {
        return $route->map(function($path) use ($subject) {
            return $this->_pathToRoute($path)->run($subject);
        });
    }

    /**
     * Given an optional result, if no result is present return
     * a default missing response
     *
     * @param Option $result
     * @return mixed
     */
    private function _fallbackToMissing(Option $result) {
        return $result->getOrElse(function() {
            $view     = new StringView("404");
            $response = new MissingResponse($view);
            return new Ok($response);
        });
    }

    abstract public function routes();

    abstract public function fallback();

}
