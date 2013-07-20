<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

/**
 * Class OkResponse
 *
 * @package util\response
 */
class MissingResponse extends AbstractHttpResponse {

    /**
     * Returns a missing status code
     *
     * @return int
     */
    public function statusCode() {
        return 404;
    }

}