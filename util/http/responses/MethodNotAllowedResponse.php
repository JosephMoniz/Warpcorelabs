<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class MethodNotAllowedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 405;
    }

}