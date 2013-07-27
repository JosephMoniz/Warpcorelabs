<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class MultiStatusResponse extends AbstractHttpResponse {

    /**
     * @return int
     */
    public function statusCode() {
        return 207;
    }

}