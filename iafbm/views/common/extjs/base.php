<?php

class CommonExtjsBaseView extends xView {

    function defaults($items) {
        foreach ($items as $name => $default) {
            $this->data[$name] = @$this->data[$name] ? $this->data[$name] : $default;
        }
    }

    /**
     * TODO
     * Returns an ExtJs column model from an xModel.
     */
    static function ext_column_model(xModel $model) {

    }

    static function ext_reader_fields(xModel $model) {

    }

}