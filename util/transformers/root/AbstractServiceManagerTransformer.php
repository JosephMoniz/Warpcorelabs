<?php
namespace util\transformers\root;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\ServiceManager;

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
        $subject->set("serviceManager", new ServiceManager($this->services()));
        return new Ok($subject);
    }

    /**
     * @return array
     */
    abstract public function services();

}
