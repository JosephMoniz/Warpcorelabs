<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class LockedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 423;
    }

}