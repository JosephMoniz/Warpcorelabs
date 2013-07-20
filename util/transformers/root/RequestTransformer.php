<?php
namespace util\transformers\root;
use PlasmaConduit\HttpRequest;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Ok;
use PlasmaConduit\ServiceManager;

/**
 * Class RequestTransformer
 *
 * @package util\transformers\root
 */
class RequestTransformer extends AbstractTransformer {

    /**
     * @param \PlasmaConduit\Map $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        $subject->set("request", new HttpRequest());
        return new Ok($subject);
    }

}
