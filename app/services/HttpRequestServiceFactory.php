<?php
namespace app\services;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\ServiceManager;
use PlasmaConduit\servicemanager\ServiceFactory;

class HttpRequestServiceFactory implements ServiceFactory {

    const ID = "request";

    public function factory(ServiceManager $serviceManager) {
        return new HttpRequest();
    }

}