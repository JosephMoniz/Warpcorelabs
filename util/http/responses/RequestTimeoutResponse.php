<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class RequestTimeoutResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 408;
    }

}