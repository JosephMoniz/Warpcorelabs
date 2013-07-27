<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class EntityTooLargeResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 413;
    }

}