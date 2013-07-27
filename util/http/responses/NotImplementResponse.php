<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class NotImplementedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 501;
    }

}