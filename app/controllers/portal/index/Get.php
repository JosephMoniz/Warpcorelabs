<?php
namespace app\controllers\portal\index;
use app\render\HtmlView;
use app\transformers\controller\EtagHitResponseTransformer;
use app\transformers\controller\EtagResponseTransformer;
use PlasmaConduit\Map;
use PlasmaConduit\pipeline\responses\Ok;
use util\http\router\AbstractRouteHandler;
use util\http\responses\OkResponse;

/**
 * Class Get
 *
 * @package app\controllers\portal\index
 */
class Get extends AbstractRouteHandler {

    /**
     * Handle the request
     *
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function handle($subject) {
        /** @var \PlasmaConduit\ServiceManager $sm  */
        /** @var \PlasmaConduit\Config $config  */
        $sm       = $subject->get("serviceManager")->get();
        $config   = $sm->get("config")->get();
        $view     = new HtmlView("portal/pages/index.php");
        $response = new OkResponse($view);
        $view->set("static_url", $config->get("static:url")->get());
        $response->withData($subject);
        return new Ok($response);
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