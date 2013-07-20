<?php
namespace util\render\views;
use PlasmaConduit\Path;
use PlasmaConduit\Template;
use util\render\View;

/**
 * Class AbstractHtmlView
 *
 * @package util\render\views
 */
abstract class AbstractHtmlView extends View {

    private $_path;

    /**
     * Constructor
     *
     * @param string $path
     * @param array $data
     */
    public function __construct($path, array $data = []) {
        parent::__construct();
        $this->_path = Path::join($this->path(), $path);
        $this->setMultiple($data);
    }

    /**
     * Returns the rendered results
     *
     * @return string
     */
    public function render() {
        return Template::render($this->_path, $this->getData()->toArray());
    }

    /**
     * Returns the path where the templates can be found
     *
     * @return string
     */
    abstract public function path();

}