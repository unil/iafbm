<?php

/**
 * @package iafbm
 * @subpackage view
 */
class LayoutPrintView extends xView {

    function init() {
        $this->meta = xUtil::array_merge($this->meta, array(
            'css' => array(
                xUtil::url('a/css/print.css', true),
            )
        ));
    }
}