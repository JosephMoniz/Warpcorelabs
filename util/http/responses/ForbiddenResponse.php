<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class ForbiddenResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 403;
    }

}