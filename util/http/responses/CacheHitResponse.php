<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

/**
 * Class CacheHitResponse
 *
 * @package util\http\responses
 */
class CacheHitResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 304;
    }

}