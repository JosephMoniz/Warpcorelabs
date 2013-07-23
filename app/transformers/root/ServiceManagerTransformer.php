<?php
namespace app\transformers\root;
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
            "config"      => 'app\services\ConfigServiceFactory',
            "environment" => 'app\services\EnvironmentServiceFactory'
        ];
    }

}
