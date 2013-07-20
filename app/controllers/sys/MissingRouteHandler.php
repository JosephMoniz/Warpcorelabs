<?php
namespace app\controllers\sys;
use PlasmaConduit\pipeline\responses\Ok;
use util\http\router\AbstractRouteHandler;
use util\render\views\StringView;
use util\http\responses\MissingResponse;

/**
 * Class MissingRouteHandler
 *
 * @package app\controllers\sys
 */
class MissingRouteHandler extends AbstractRouteHandler {

    /**
     * Display a 404 message
     *
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function handle($subject) {
        $view     = new StringView("Hmm .. can't seem to find that page");
        $response = new MissingResponse($view);
        return new Ok($response);
    }

}