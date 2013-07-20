<?php
namespace util\render;
use PlasmaConduit\Map;

/**
 * Class View
 *
 * @package util\render
 */
abstract class View {

    /**
     * The map of the data to be used rendering the results
     *
     * @var \PlasmaConduit\Map
     */
    private $_data;

    /**
     * Constructor
     */
    public function __construct() {
        $this->_data = new Map();
    }

    /**
     * The rendering method
     *
     * @return string
     */
    abstract public function render();

    /**
     * Return a map of the data that will be used for rendering the result
     *
     * @return Map
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Return a specific key form the data that will be used for rendering
     * the result
     *
     * @param $key
     * @return \PlasmaConduit\option\Option
     */
    public function get($key) {
        return $this->_data->get($key);
    }

    /**
     * Add a parameter to the data that will be used to render the result
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value) {
        $this->_data->set($key, $value);
        return $this;
    }

    /**
     * Sets multiple parameters to be used when rendering the result.
     *
     * @param array $values
     * @return $this
     */
    public function setMultiple(array $values) {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

}