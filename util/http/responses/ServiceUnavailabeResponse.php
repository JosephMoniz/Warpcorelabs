<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class ServiceUnavailableResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 503;
    }

}