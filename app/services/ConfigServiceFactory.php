<?php
namespace app\services;
use PlasmaConduit\Apc;
use PlasmaConduit\Config;
use PlasmaConduit\config\caches\ApcConfigCache;
use PlasmaConduit\config\caches\VoidConfigCache;
use PlasmaConduit\config\paths\ConfigPathRecursiveDirectory;
use PlasmaConduit\Path;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\servicemanager\ServiceFactory;
use util\environments\Environment;

/**
 * Class ConfigServiceFactory
 *
 * @package app\services
 */
class ConfigServiceFactory implements ServiceFactory {

    /**
     * @param ServiceManager $serviceManager
     * @return Config
     */
    public function factory(ServiceManager $serviceManager) {
        $apc         = $serviceManager->get("apc")->get();
        $environment = $serviceManager->get("environment")->get();
        $base        = Path::join(dirname(__FILE__), "../../config");
        $common      = self::_getCommonPath($base);
        $targetEnv   = self::_getPathFromEnvironment($environment, $base);
        $local       = self::_getLocalPath($base);
        $cache       = self::_getCacheFromEnvironment($environment, $apc);
        return new Config($cache, [$common, $targetEnv, $local]);
    }

    /**
     * @param $base
     * @return ConfigPathRecursiveDirectory
     */
    private static function _getCommonPath($base) {
        return new ConfigPathRecursiveDirectory(Path::join($base, "common"));
    }

    /**
     * @param Environment $env
     * @param $base
     * @return ConfigPathRecursiveDirectory
     */
    private static function _getPathFromEnvironment(Environment $env, $base) {
        if ($env->isProduction()) {
            return new ConfigPathRecursiveDirectory(Path::join($base, "prod"));
        } else {
            return new ConfigPathRecursiveDirectory(Path::join($base, "dev"));
        }
    }

    /**
     * @param $base
     * @return ConfigPathRecursiveDirectory
     */
    private static function _getLocalPath($base) {
        return new ConfigPathRecursiveDirectory(Path::join($base, "local"));
    }

    /**
     * @param Environment $env
     * @param Apc $apc
     * @return \PlasmaConduit\AbstractConfigCache
     */
    private static function _getCacheFromEnvironment(Environment $env, Apc $apc) {
        if ($env->isProduction()) {
            return new ApcConfigCache("__config:", $apc);
        } else {
            return new VoidConfigCache();
        }
    }

}