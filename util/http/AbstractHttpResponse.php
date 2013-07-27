<?php
namespace util\http;
use PlasmaConduit\AbstractView;
use PlasmaConduit\headers\AbstractHttpResponseHeader;
use PlasmaConduit\Map;

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
     * @var AbstractView
     */
    private $_view;

    /**
     * Arbitrary data to stash along for the ride
     *
     * @var \PlasmaConduit\Map
     */
    private $_data;

    /**
     * The http status code to use in the response
     *
     * @return Int
     */
    abstract public function statusCode();

    /**
     * The constructor
     *
     * @param AbstractView $view
     */
    public function __construct(AbstractView $view) {
        $this->_view    = $view;
        $this->_headers = new Map();
        $this->_data    = new Map();
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
     * @return $this
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
     * Arbitrary data to stash along for the ride
     *
     * @param Map $data
     * @return AbstractHttpResponse
     */
    public function withData(Map $data) {
        $this->_data = $this->_data->merge($data);
        return $this;
    }

    /**
     * Returns the data we had stashed away
     *
     * @return Map
     */
    public function data() {
        return $this->_data;
    }

    /**
     * Returns the view that will be used to render the response
     *
     * @return AbstractView
     */
    public function view() {
        return $this->_view;
    }

}