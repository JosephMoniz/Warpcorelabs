<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class UriTooLongResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 415;
    }

}