<?php
namespace util\render\views;
use util\render\View;

/**
 * Class StringView
 *
 * @package util\render\views
 */
class StringView extends View {

    /**
     * The string to return as the rendered result
     *
     * @var string
     */
    private $_string;

    /**
     * Constructor
     *
     * @param string $str
     */
    public function __construct($str) {
        $this->_string = $str;
    }

    /**
     * Returns the string passed in to the constructor
     *
     * @return string
     */
    public function render() {
        return $this->_string;
    }

}