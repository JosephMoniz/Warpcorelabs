<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class PreconditionFailedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 412;
    }

}