<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class FoundResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 302;
    }

}