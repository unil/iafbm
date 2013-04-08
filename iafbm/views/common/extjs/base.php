<?php

/**
 * @package iafbm
 * @subpackage view
 */
class CommonExtjsBaseView extends xView {

    function defaults($items) {
        foreach ($items as $name => $default) {
            $this->data[$name] = isset($this->data[$name]) ? $this->data[$name] : $default;
        }
    }

    /**
     * TODO
     * Returns an ExtJs column model from an xModel.
     */
    static function ext_column_model(xModel $model) {}
}