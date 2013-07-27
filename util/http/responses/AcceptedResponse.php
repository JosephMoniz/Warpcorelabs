<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class AcceptedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 202;
    }

}