<?php
namespace util;
use PlasmaConduit\AbstractHttpResponse;
use PlasmaConduit\HttpResponseConsumer;
use PlasmaConduit\Map;
use PlasmaConduit\pipeline\AbstractResponse;
use PlasmaConduit\pipeline\Pipeline;

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
     * @var callable
     */
    private $_errorHandler;

    /**
     * @param array $pipeline
     * @param callable $errorHandler
     */
    public function __construct(array $pipeline, callable $errorHandler) {
        $this->_pipeline     = new Pipeline($pipeline);
        $this->_errorHandler = $errorHandler;
    }

    /**
     * @return string
     */
    public function convey() {
        $response = $this->_pipeline->run(new Map());
        return $this->_handleResponse($response);
    }

    /**
     * Consumes a response and returns any rendered data to be displayed
     *
     * @param AbstractResponse $response
     * @return string
     * @throws \Exception
     */
    private function _handleResponse(AbstractResponse $response) {
        switch($response->getType()) {
            case "Ok":    return $this->_handleOk($response->getValue());
            case "Done":  return $this->_handleOk($response->getValue());
            case "Error": return $this->_handleError($response->getValue());
            default:      throw new \Exception("Unknown response type");
        }
    }

    /**
     * If we have a successful response, lets handle it properly
     *
     * @param AbstractHttpResponse $response
     * @return string
     */
    private function _handleOk(AbstractHttpResponse $response) {
        $consumer = new HttpResponseConsumer();
        return $consumer->consume($response);
    }

    /**
     * Given an Error response, handle it accordingly
     *
     * @param mixed $result
     * @return string
     */
    private function _handleError($result) {
        $handler = $this->_errorHandler;
        return $handler($result);
    }

}