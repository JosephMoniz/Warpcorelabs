<?php
namespace util\http;
use PlasmaConduit\AbstractHttpResponse;
use PlasmaConduit\headers\AbstractHttpResponseHeader;
use PlasmaConduit\ResponseHeaders;

/**
 * Class HttpResponseConsumer
 *
 * @package util\http
 */
class HttpResponseConsumer {

    /**
     * The response headers object
     *
     * @var \PlasmaConduit\ResponseHeaders
     */
    private $_headers;

    /**
     * Constructor
     */
    public function __construct() {
        $this->_headers = new ResponseHeaders();
    }

    /**
     * Given an Ok response, handle it accordingly
     *
     * @param AbstractHttpResponse $response
     * @return string
     */
    public function consume(AbstractHttpResponse $response) {
        return $this->_setStatusCode($response)
                    ->_setHeaders($response)
                    ->_renderView($response);
    }

    /**
     * Given a response object set the status code of the http response
     *
     * @param AbstractHttpResponse $response
     * @return HttpResponseConsumer $this
     */
    private function _setStatusCode(AbstractHttpResponse $response) {
        http_response_code($response->statusCode());
        return $this;
    }

    /**
     * Given a response object, set the http headers of the http response
     *
     * @param AbstractHttpResponse $response
     * @return HttpResponseConsumer $this
     */
    private function _setHeaders(AbstractHttpResponse $response) {
        $response->headers()->each(function(AbstractHttpResponseHeader $header) {
            $this->_headers->set($header);
        });
        return $this;
    }

    /**
     * Given a response object, render the view and return it for display
     *
     * @param AbstractHttpResponse $response
     * @return string
     */
    private function _renderView(AbstractHttpResponse $response) {
        return $response->view()->render();
    }

}