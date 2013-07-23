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
     * @var \PlasmaConduit\pipeline\Pipeline
     */
    private $_pipeline;

    /**
     * @param array $pipeline
     */
    public function __construct(array $pipeline) {
        $this->_pipeline = new Pipeline($pipeline);
    }

    /**
     * @return string
     */
    public function convey() {
        $response = $this->_pipeline->run(new Map());
        $consumer = new HttpResponseConsumer();
        return $consumer->consume($response);
    }

}