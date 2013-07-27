<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class BadGatewayResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 502;
    }

}