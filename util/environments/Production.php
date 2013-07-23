<?php
namespace util\environments;
use util\environments\Environment;

/**
 * Class Production
 *
 * @package util\environments
 */
class Production implements Environment {

    /**
     * @return bool
     */
    public function isDevelopment() {
        return false;
    }

    /**
     * @return bool
     */
    public function isProduction() {
        return true;
    }

}