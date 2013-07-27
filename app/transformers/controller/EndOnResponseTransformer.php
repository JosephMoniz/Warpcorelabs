<?php
namespace app\transformers\controller;
use PlasmaConduit\AbstractHttpResponse;
use PlasmaConduit\pipeline\AbstractTransformer;
use PlasmaConduit\pipeline\responses\Done;
use PlasmaConduit\pipeline\responses\Ok;

/**
 * Class EndOnResponseTransformer
 *
 * @package app\transformers\controller
 */
class EndOnResponseTransformer extends AbstractTransformer {

    /**
     * @param $subject
     * @return \PlasmaConduit\pipeline\AbstractResponse
     */
    public function apply($subject) {
        if ($subject instanceof AbstractHttpResponse) {
            return new Done($subject);
        } else {
            return new Ok($subject);
        }
    }

}