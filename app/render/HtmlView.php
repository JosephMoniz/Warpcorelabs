<?php
namespace app\render;
use PlasmaConduit\Path;
use util\render\views\AbstractHtmlView;

/**
 * Class HtmlView
 *
 * @package app\render
 */
class HtmlView extends AbstractHtmlView {

    /**
     * Returns the path to the templates
     *
     * @return string
     */
    public function path() {
        return Path::join(dirname(__FILE__), "../views");
    }

}