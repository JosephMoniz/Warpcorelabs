<?php
namespace util\environments;
use util\environments\Environment;

/**
 * Class Development
 *
 * @package util\environments
 */
class Development implements Environment {

    /**
     * @return bool
     */
    public function isDevelopment() {
        return true;
    }

    /**
     * @return bool
     */
    public function isProduction() {
        return false;
    }

}