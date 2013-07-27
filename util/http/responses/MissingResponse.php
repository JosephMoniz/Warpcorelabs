<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class MissingResponse extends AbstractHttpResponse {

    /**
     * Returns a missing status code
     *
     * @return int
     */
    public function statusCode() {
        return 404;
    }

}