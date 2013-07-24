<?php
namespace util\render\views;
use PlasmaConduit\option\None;
use PlasmaConduit\option\Some;
use PlasmaConduit\Path;
use PlasmaConduit\Template;
use util\render\View;

/**
 * Class AbstractHtmlView
 *
 * @package util\render\views
 */
abstract class AbstractHtmlView extends View {

    /**
     * @var string
     */
    private $_path;

    /**
     * @var \PlasmaConduit\option\Option
     */
    private $_cached;

    /**
     * Constructor
     *
     * @param string $path
     * @param array $data
     */
    public function __construct($path, array $data = []) {
        parent::__construct();
        $this->_path   = Path::join($this->path(), $path);
        $this->_cached = new None();
        $this->setMultiple($data);
    }

    /**
     * Returns the rendered results
     *
     * @return string
     */
    public function render() {
        if ($this->_cached->isEmpty()) {
            $rendered = Template::render($this->_path, $this->getData()->toArray());
            $this->_cached = new Some($rendered);
        }
        return $this->_cached->get();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return View
     */
    public function set($key, $value) {
        if ($this->_cached->nonEmpty()) {
            $this->_cached = new None();
        }
        parent::set($key, $value);
    }

    /**
     * Returns the path where the templates can be found
     *
     * @return string
     */
    abstract public function path();

}