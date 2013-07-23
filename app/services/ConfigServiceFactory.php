<?php
namespace app\services;
use PlasmaConduit\Config;
use PlasmaConduit\Path;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\servicemanager\ServiceFactory;
use util\environments\Environment;

class ConfigServiceFactory implements ServiceFactory {

    public static function factory(ServiceManager $serviceManager) {
        $environment = $serviceManager->get("environment")->get();
        $base        = Path::join(dirname(__FILE__), "../config");
        $common      = self::_getCommonPath($base);
        $targetEnv   = self::_getPathFromEnvironment($environment, $base);
        $local       = self::_getLocalPath($base);
        $cache       = self::_getCacheFromEnvironment($environment, $base);
        return new Config($cache, [$common, $targetEnv, $local]);
    }

    private static function _getCommonPath($base) {
        return new ConfigPathRecursiveDirectory(Path::join($base, "common"));
    }

    private static function _getPathFromEnvironment(Environment $env, $base) {
        if ($env->isProduction()) {
            return new ConfigPathRecursiveDirectory(Path::join($base, "prod"));
        } else {
            return new ConfigPathRecursiveDirectory(Path::join($base, "dev"));
        }
    }

    private static function _getLocalPath($base) {
        return new ConfigPathRecursiveDirectory(Path::join($base, "local"));
    }

    private static function _getCacheFromEnvironment(Environment $env) {
        if ($env->isProduction()) {
            return new ApcConfigCache();
        } else {
            return new VoidConfigCache();
        }
    }

}