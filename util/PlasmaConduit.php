<?php
namespace util;
use PlasmaConduit\Map;
use PlasmaConduit\pipeline\Pipeline;
use util\http\HttpResponseConsumer;
use util\transformers\root\RequestTransformer;

/**
 * Class PlasmaConduit
 * @package util
 */
class PlasmaConduit {

    /**
     * @var array
     */
    private $_pipeline;

    /**
     * @param array $pipeline
     */
    public function __construct(array $pipeline) {
        $this->_transformers = new Pipeline(array_merge(
            [ new RequestTransformer() ],
            $pipeline
        ));
    }

    /**
     * @return string
     */
    public function convey() {
        $response = $this->_transformers->run(new Map());
        $consumer = new HttpResponseConsumer();
        return $consumer->consume($response);
    }

}