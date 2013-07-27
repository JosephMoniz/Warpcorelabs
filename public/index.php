<?php
include "../vendor/autoload.php";
use app\pipelines\root\LessPipeline;
use app\transformers\root\RouterTransformer;
use app\transformers\root\ServiceManagerTransformer;
use PlasmaConduit\Map;
use PlasmaConduit\Path;
use util\PlasmaConduit;

// Booo!, clean this up a bit.
$base = dirname(__FILE__);
$path = Path::join($base, $_SERVER["REQUEST_URI"]);
if (strncmp($base, $path, strlen($base)) == 0 &&
    !preg_match("/index\\.php$|^\\/$/", $_SERVER["REQUEST_URI"]) &&
    file_exists($path))
{
    return false;
}

$conduit = new PlasmaConduit(
    [
        new ServiceManagerTransformer(),
        new LessPipeline(),
        new RouterTransformer()
    ],
    function() {
        return "idk, error handling stuff";
    }
);

echo $conduit->convey();