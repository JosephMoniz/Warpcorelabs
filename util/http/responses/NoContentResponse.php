<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class NoContentResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 204;
    }

}