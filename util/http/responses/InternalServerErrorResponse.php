<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class InternalServerErrorResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 500;
    }

}