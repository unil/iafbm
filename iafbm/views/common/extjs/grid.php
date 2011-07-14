<?php

xView::load('common/extjs/base');

class CommonExtjsGridView extends CommonExtjsBaseView {

    function init() {
        // Setups default data values
        $this->defaults(array(
            'title' => 'Elements',
            'id' => sha1(microtime().session_id()),
            'pageSize' => 25,
            'height' => 600
        ));
        // Checks madatory data values
        if (!@$this->data['model']) throw new xException('model data missing: mandatory for Ext.grid.GridPanel');
    }
}