<?php
namespace util\services;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\servicemanager\ServiceFactory;

class HttpRequestServiceFactory implements ServiceFactory {

    public static function factory(ServiceManager $serviceManager) {
        return new HttpRequest();
    }

}