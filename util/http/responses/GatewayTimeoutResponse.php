<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class GatewayTimeoutResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 504;
    }

}