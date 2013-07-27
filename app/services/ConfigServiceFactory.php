<?php
namespace app\services;
use PlasmaConduit\Apc;
use PlasmaConduit\Config;
use PlasmaConduit\Environment;
use PlasmaConduit\config\caches\ApcConfigCache;
use PlasmaConduit\config\caches\VoidConfigCache;
use PlasmaConduit\config\paths\ConfigPathRecursiveDirectory;
use PlasmaConduit\Path;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\servicemanager\ServiceFactory;

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
        $base        = $this->_getBasePath();
        $common      = $this->_getCommonPath($base);
        $targetEnv   = $this->_getPathFromEnvironment($environment, $base);
        $local       = $this->_getLocalPath($base);
        $cache       = $this->_getCacheFromEnvironment($environment, $apc);
        return new Config($cache, [$common, $targetEnv, $local]);
    }

    /**
     * @return string
     */
    private function _getBasePath() {
        return Path::join(dirname(__FILE__), "../../config");
    }

    /**
     * @param $base
     * @return ConfigPathRecursiveDirectory
     */
    private function _getCommonPath($base) {
        return new ConfigPathRecursiveDirectory(Path::join($base, "common"));
    }

    /**
     * @param Environment $environment
     * @param $base
     * @return ConfigPathRecursiveDirectory
     */
    private function _getPathFromEnvironment(Environment $environment, $base) {
        $folder = $this->_environmentToFolder($environment);
        return new ConfigPathRecursiveDirectory(Path::join($base, $folder));
    }

    /**
     * @param Environment $environment
     * @return string
     */
    private function _environmentToFolder(Environment $environment) {
        switch($environment->getType()) {
            case "Development": return "dev";
            case "Test":        return "test";
            case "Stage":       return "stage";
            case "Production":  return "prod";
        }
    }

    /**
     * @param $base
     * @return ConfigPathRecursiveDirectory
     */
    private function _getLocalPath($base) {
        return new ConfigPathRecursiveDirectory(Path::join($base, "local"));
    }

    /**
     * @param Environment $environment
     * @param Apc $apc
     * @return \PlasmaConduit\AbstractConfigCache
     */
    private function _getCacheFromEnvironment(Environment $environment,
                                              Apc $apc)
    {
        if ($environment->isProduction()) {
            return new ApcConfigCache("__config:", $apc);
        } else {
            return new VoidConfigCache();
        }
    }

}