<?php
namespace app\controllers\portal\index;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\responses\OkResponse;
use app\render\HtmlView;
use PlasmaConduit\AbstractHttpRouteHandler;
use PlasmaConduit\Map;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\transformers\EtagHitResponseTransformer;
use PlasmaConduit\transformers\EtagResponseTransformer;

/**
 * Class Get
 *
 * @package app\controllers\portal\index
 */
class Get extends AbstractHttpRouteHandler {

    /**
     * Handle the request
     *
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function handle($subject) {
        $sm       = $this->_getServiceManagerFromSubject($subject);
        $config   = $this->_getConfigFromServiceManager($sm);
        $view     = new HtmlView("portal/pages/index.php");
        $response = new OkResponse($view);
        $view->set("static_url", $config->get("static:url")->get());
        $response->withData($subject);
        return new Ok($response);
    }

    /**
     * @param Map $subject
     * @return ServiceManager
     */
    private function _getServiceManagerFromSubject(Map $subject) {
        return $subject->get("serviceManager")->get();
    }

    /**
     * @param ServiceManager $sm
     * @return \PlasmaConduit\Config
     */
    private function _getConfigFromServiceManager(ServiceManager $sm) {
        return $sm->get("config")->get();
    }

    /**
     * @return array
     */
    public function postHandler() {
        return [
            new EtagHitResponseTransformer(),
            new EtagResponseTransformer()
        ];
    }

}