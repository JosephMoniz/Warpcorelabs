<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class ExpectationFailedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 417;
    }

}