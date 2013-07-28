<?php
include "../vendor/autoload.php";
use app\pipelines\root\LessPipeline;
use app\transformers\ServiceManagerTransformer;
use app\transformers\RouterTransformer;
use PlasmaConduit\Map;
use PlasmaConduit\Path;
use PlasmaConduit\PlasmaConduit;

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
        return "Whoa, something went wrong here.";
    }
);

echo $conduit->convey();