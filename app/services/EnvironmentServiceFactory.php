<?php
namespace app\services;
use PlasmaConduit\environments\Development;
use PlasmaConduit\environments\Production;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\headers\request\HostHeader;
use PlasmaConduit\option\None;
use PlasmaConduit\option\Option;
use PlasmaConduit\option\Some;
use PlasmaConduit\servicemanager\ServiceFactory;

/**
 * Class EnvironmentServiceFactory
 *
 * @package app\services
 */
class EnvironmentServiceFactory implements ServiceFactory {

    const ID = "environment";

    /**
     * @param ServiceManager $serviceManager
     * @return mixed
     */
    public function factory(ServiceManager $serviceManager) {
        $request = $this->_getRequest($serviceManager);
        $host    = $request->getHeader(new HostHeader());
        return self::_getEnvironmentFromHost($host);
    }

    /**
     * @param ServiceManager $serviceManager
     *
     * @return \PlasmaConduit\HttpRequest
     */
    private function _getRequest(ServiceManager $serviceManager) {
        $request = $serviceManager->get(HttpRequestServiceFactory::ID);
        return $request->get();
    }

    /**
     * @param Option $host
     * @return mixed
     */
    private function _getEnvironmentFromHost(Option $host) {
        $production  = $this->_getProductionIfValid($host);
        return self::_failOverToDevelopment($production);
    }

    /**
     * @param Option $host
     * @return Option
     */
    private function _getProductionIfValid(Option $host) {
        return $host->flatMap(function(HostHeader $host) {
            $value = $host->getValue();
            if (strpos($value, "warpcorelabs.com") !== false) {
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
    private function _failOverToDevelopment(Option $environment) {
        return $environment->getOrElse(function() {
            return new Development();
        });
    }

}