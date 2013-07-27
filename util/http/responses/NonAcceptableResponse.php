<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class NonAcceptableResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 406;
    }

}