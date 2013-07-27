<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class SeeOtherResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 303;
    }

}