<?php
namespace app\controllers\sys;
use PlasmaConduit\AbstractHttpRouteHandler;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\responses\MissingResponse;
use PlasmaConduit\views\StringView;

/**
 * Class MissingRouteHandler
 *
 * @package app\controllers\sys
 */
class MissingRouteHandler extends AbstractHttpRouteHandler {

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