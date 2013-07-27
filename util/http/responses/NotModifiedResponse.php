<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class NotModifiedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 304;
    }

}