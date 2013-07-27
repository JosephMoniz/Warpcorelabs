<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class ResetContentResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 205;
    }

}