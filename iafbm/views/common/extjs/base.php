<?php

/**
 * @package iafbm
 * @subpackage view
 */
class CommonExtjsBaseView extends xView {

    /**
     * Creates default class properties from a definition array.
     * Properties already set will not be overridden.
     * @param array Key/value pairs of default properties to apply to this instance.
     */
    function defaults($items) {
        foreach ($items as $name => $default) {
            $this->data[$name] = isset($this->data[$name]) ? $this->data[$name] : $default;
        }
    }
}