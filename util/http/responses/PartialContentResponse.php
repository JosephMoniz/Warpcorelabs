<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class PartialContentResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 206;
    }

}