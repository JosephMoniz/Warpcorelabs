<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class GoneResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 410;
    }

}