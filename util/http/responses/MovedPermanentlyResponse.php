<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class MovedPermanentlyResponse extends AbstractHttpResponse {

    /**
     * @return int
     */
    public function statusCode() {
        return 301;
    }

}