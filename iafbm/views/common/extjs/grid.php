<?php

xView::load('common/extjs/base');

/**
 * @package iafbm
 * @subpackage view
 */
class CommonExtjsGridView extends CommonExtjsBaseView {

    /**
     * Defines default ExtJS grid properties.
     */
    function init() {
        // Checks madatory data values
        if (!@$this->data['model']) throw new xException('model data missing: mandatory for Ext.grid.GridPanel');
        // Setups default data values
        $this->defaults(array(
            'title' => 'Elements',
            'id' => sha1(microtime().session_id()),
            'pageSize' => 25,
            'height' => 600,
            'columns' => @$this->data['columns'] ? $this->data['columns'] : "iafbm.columns.{$this->data['model']}",
            'editable' => true,
            'autoSync' => false,
            'store-params' => new stdClass()
        ));
    }
}