<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class UnsupportedMediaTypeResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 415;
    }

}