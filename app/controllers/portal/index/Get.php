<?php
namespace app\controllers\portal\index;
use PlasmaConduit\headers\request\HostHeader;
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
        /** @var \PlasmaConduit\HttpRequest $request  */
        $request = $subject->get("request")->get();
        $view    = new HtmlView("portal/pages/index.php");
        $view->set("host", $request->getHeader(new HostHeader()));
        $response = new OkResponse($view);
        return new Ok($response);
    }

}