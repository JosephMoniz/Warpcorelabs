<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class CreatedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 201;
    }

}