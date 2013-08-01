<?php
namespace app\pipelines\root;
use PlasmaConduit\Path;
use PlasmaConduit\pipelines\AbstractLessPipeline;

class LessPipeline extends AbstractLessPipeline {

    public function path() {
        return Path::join(dirname(__DIR__), "../../public");
    }

}