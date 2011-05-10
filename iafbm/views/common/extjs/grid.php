<?php

xView::load('common/extjs/base');

class CommonExtjsGridView extends CommonExtjsBaseView {

    function init() {
        // Setups js additional includes
        $this->add_meta(array('js' => array(xUtil::url('a/js/ext-ux/native/form/SearchField.js'))));
        // Setups default data values
        $this->defaults(array(
            'title' => 'Elements',
            'id' => sha1(microtime().session_id()),
            'renderTo' => 'editor-grid',
            'var' => 'grid'
        ));
        // Checks madatory data values
        if (!@$this->data['columns']) throw new xException('columns data missing: mandatory for Ext.grid.GridPanel');
        if (!@$this->data['model']) throw new xException('model data missing: mandatory for Ext.grid.GridPanel');
    }
}