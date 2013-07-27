<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class TemporaryRedirectResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 307;
    }

}