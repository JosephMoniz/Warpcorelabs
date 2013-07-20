<?php
namespace app\controllers\portal\index;
use app\render\HtmlView;
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
        $view     = new HtmlView("portal/pages/index.php");
        $response = new OkResponse($view);
        return new Ok($response);
    }

}