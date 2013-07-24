<?php
namespace util\transformers\root;
use PlasmaConduit\Map;
use PlasmaConduit\option\Option;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;
use util\http\HttpRequest;
use util\http\HttpRouter;
use util\http\responses\MissingResponse;
use util\http\router\AbstractRouteHandler;
use util\render\views\StringView;

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
        return $route->map(function($path) use ($subject) {
            /** @var AbstractRouteHandler $route */
            $route = new $path();
            return $route->run($subject);
        })->getOrElse(new Ok(new MissingResponse(new StringView("404"))));
    }

    abstract public function routes();

    abstract public function fallback();

}
