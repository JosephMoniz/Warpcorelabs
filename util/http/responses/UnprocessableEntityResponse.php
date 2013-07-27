<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class UnprocessableEntityResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 422;
    }

}