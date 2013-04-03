<?php

/**
 * @package iafbm
 * @subpackage view
 */
class LayoutPrintView extends xView {

    /**
     * Adds print CSS to loaded resources.
     */
    function init() {
        $this->meta = xUtil::array_merge($this->meta, array(
            'css' => array(
                xUtil::url('a/css/print.css', true),
            )
        ));
    }
}