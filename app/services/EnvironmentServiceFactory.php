<?php
namespace app\services;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\headers\request\HostHeader;
use PlasmaConduit\option\None;
use PlasmaConduit\option\Option;
use PlasmaConduit\option\Some;
use PlasmaConduit\servicemanager\ServiceFactory;
use util\environments\Development;
use util\environments\Production;

/**
 * Class EnvironmentServiceFactory
 *
 * @package app\services
 */
class EnvironmentServiceFactory implements ServiceFactory {

    public static function factory(ServiceManager $serviceManager) {
        $request = self::_getRequest($serviceManager);
        $host    = $request->getHeader(new HostHeader());
        return self::_getEnvironmentFromHost($host);
    }

    /**
     * @param ServiceManager $serviceManager
     *
     * @return \PlasmaConduit\HttpRequest
     */
    private static function _getRequest(ServiceManager $serviceManager) {
        /** @var \PlasmaConduit\option\Option $request */
        $request = $serviceManager->get("request");
        return $request->get();
    }

    /**
     * @param Option $host
     * @return mixed
     */
    private static function _getEnvironmentFromHost(Option $host) {
        $production  = self::_getProductionIfValid($host);
        return self::_failOverToDevelopment($production);
    }

    /**
     * @param Option $host
     * @return Option
     */
    private static function _getProductionIfValid(Option $host) {
        return $host->flatMap(function(HostHeader $host) {
            $value = $host->getValue();
            if (strpos($value, "warpcorelabs.com") > 0) {
                return new Some(new Production());
            } else {
                return new None();
            }
        });
    }

    /**
     * @param Option $environment
     * @return mixed
     */
    private static function _failOverToDevelopment(Option $environment) {
        return $environment->getOrElse(function() {
            return new Development();
        });
    }

}