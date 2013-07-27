<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class HttpVersionNotSupportedResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 505;
    }

}