<?php
namespace app\transformers;
use app\services\ApcServiceFactory;
use app\services\ConfigServiceFactory;
use app\services\EnvironmentServiceFactory;
use app\services\HttpRequestServiceFactory;
use PlasmaConduit\transformers\AbstractServiceManagerTransformer;

/**
 * Class ServiceManagerTransformer
 *
 * @package app\transformers\root
 */
class ServiceManagerTransformer extends AbstractServiceManagerTransformer {

    const ID = "serviceManager";

    /**
     * @return array
     */
    public function services() {
        return [
            ApcServiceFactory::ID         => new ApcServiceFactory(),
            ConfigServiceFactory::ID      => new ConfigServiceFactory(),
            EnvironmentServiceFactory::ID => new EnvironmentServiceFactory(),
            HttpRequestServiceFactory::ID => new HttpRequestServiceFactory()
        ];
    }

}
