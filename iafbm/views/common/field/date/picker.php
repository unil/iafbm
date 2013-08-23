<?php

/**
 * Uses http://www.monkeyphysics.com/mootools/script/2/datepicker
 *
 */
/**
 * @package iafbm
 * @subpackage view
 */
class AllFieldDatePickerView extends xView {

    function init() {
        $this->add_meta(array(
            'js' => array(
                xUtil::url('a/js/mootools/mootools-core-1.3-full-compat-yc.js'),
                xUtil::url('a/js/mootools/ux/datepicker/datepicker-minified.js')
            ),
            'css' => array(
                xUtil::url('a/js/mootools/ux/datepicker/datepicker.css'),
                xUtil::url('a/js/mootools/ux/datepicker/skins/vista/datepicker_vista.css')
            )
        ));
    }
}