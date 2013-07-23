<?php
namespace util\environments;

/**
 * Class Environment
 *
 * @package util\environments
 */
interface Environment {

    /**
     * @return bool
     */
    public function isDevelopment();

    /**
     * @return bool
     */
    public function isProduction();

}