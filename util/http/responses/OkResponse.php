<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

/**
 * Class OkResponse
 *
 * @package util\response
 */
class OkResponse extends AbstractHttpResponse {

    /**
     * Returns a success status code
     *
     * @return int
     */
    public function statusCode() {
        return 200;
    }

}