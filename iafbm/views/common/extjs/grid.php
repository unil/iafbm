<?php

xView::load('common/extjs/base');

class CommonExtjsGridView extends CommonExtjsBaseView {

    function init() {
        // Setups default data values
        $this->defaults(array(
            'title' => 'Elements',
            'id' => sha1(microtime().session_id()),
            'renderTo' => 'editor-grid',
            'var' => 'grid'
        ));
        // Checks madatory data values
        if (!@$this->data['model']) throw new xException('model data missing: mandatory for Ext.grid.GridPanel');
    }
}