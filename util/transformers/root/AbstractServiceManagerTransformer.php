<?php
namespace util\transformers\root;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\ServiceManager;
use util\services\HttpRequestServiceFactory;

/**
 * Class AbstractServiceManagerTransformer
 *
 * @package util\transformers\root
 */
abstract class AbstractServiceManagerTransformer extends AbstractTransformer {

    /**
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        $services = array_merge(
            [ "request" => new HttpRequestServiceFactory() ],
            $this->services()
        );
        $subject->set("serviceManager", new ServiceManager($services));
        return new Ok($subject);
    }

    /**
     * @return array
     */
    abstract public function services();

}
