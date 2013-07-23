<?php
namespace app\services;
use PlasmaConduit\Apc;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\servicemanager\ServiceFactory;

class ApcServiceFactory implements ServiceFactory {

    public static function factory(ServiceManager $serviceManager) {
        return new Apc();
    }

}