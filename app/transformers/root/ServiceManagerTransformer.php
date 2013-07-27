<?php
namespace app\transformers\root;
use app\services\ApcServiceFactory;
use app\services\ConfigServiceFactory;
use app\services\EnvironmentServiceFactory;
use app\services\HttpRequestServiceFactory;
use util\transformers\root\AbstractServiceManagerTransformer;

/**
 * Class ServiceManagerTransformer
 *
 * @package app\transformers\root
 */
class ServiceManagerTransformer extends AbstractServiceManagerTransformer {

    /**
     * @return array
     */
    public function services() {
        return [
            "apc"         => new ApcServiceFactory(),
            "config"      => new ConfigServiceFactory(),
            "environment" => new EnvironmentServiceFactory(),
            "request"     => new HttpRequestServiceFactory()
        ];
    }

}
