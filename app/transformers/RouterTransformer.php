<?php
namespace app\transformers;
use app\controllers\sys\MissingRouteHandler;
use PlasmaConduit\transformers\AbstractRouterTransformer;

class RouterTransformer extends AbstractRouterTransformer {

    public function routes() {
        return [
            "r:/.*/" => [
                "GET" => [
                    "/" => 'app\controllers\portal\index\Get'
                ]
            ]
        ];
    }

    public function fallback() {
        return new MissingRouteHandler();
    }

}