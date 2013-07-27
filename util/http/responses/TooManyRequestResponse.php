<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class TooManyRequestResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 429;
    }

}