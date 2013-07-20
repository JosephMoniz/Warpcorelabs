<?php
namespace util\render\views;
use util\render\View;

/**
 * Class EmptyView
 *
 * @package util\render\views
 */
class EmptyView extends View {

    /**
     * Always returns an empty string
     *
     * @return string
     */
    public function render() {
        return "";
    }

}