<?php
namespace util\http\responses;
use util\http\AbstractHttpResponse;

class InsuffecientStorageResponse extends AbstractHttpResponse {

    /**
     * @return Int
     */
    public function statusCode() {
        return 507;
    }

}