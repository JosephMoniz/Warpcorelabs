<?php
namespace app\transformers\root;
use PlasmaConduit\Path;
use util\transformers\root\AbstractLessTransformer;

class LessTransformer extends AbstractLessTransformer {

    public function path() {
        return Path::join(dirname(__DIR__), "../../public");
    }

}