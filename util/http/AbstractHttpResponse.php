<?php
namespace util\http;
use PlasmaConduit\headers\AbstractHttpResponseHeader;
use PlasmaConduit\Map;
use util\render\View;

/**
 * Class AbstractHttpResponse
 *
 * @package util\http
 */
abstract class AbstractHttpResponse {

    /**
     * The map of outbound headers to add to the response
     *
     * @var \PlasmaConduit\Map
     */
    private $_headers;

    /**
     * The view to use to render the response
     *
     * @var \util\render\View
     */
    private $_view;

    /**
     * The http status code to use in the response
     *
     * @return Int
     */
    abstract public function statusCode();

    /**
     * The constructor
     *
     * @param View $view
     */
    public function __construct(View $view) {
        $this->_view    = $view;
        $this->_headers = new Map();
    }

    /**
     * Adds a http header to the response
     *
     * @param AbstractHttpResponseHeader $header
     * @return AbstractHttpResponse
     */
    public function withHeader(AbstractHttpResponseHeader $header) {
        $this->_headers->push($header);
        return $this;
    }

    /**
     * Adds an array of http headers to the response
     *
     * @param array $headers
     * @return AbstractHttpResponse
     */
    public function withHeaders(array $headers) {
        foreach ($headers as $header) {
            $this->withHeader($header);
        }
        return $this;
    }

    /**
     * Returns a map of outbound headers
     *
     * @return Map
     */
    public function headers() {
        return $this->_headers;
    }

    /**
     * Returns the view that will be used to render the response
     *
     * @return View
     */
    public function view() {
        return $this->_view;
    }

}