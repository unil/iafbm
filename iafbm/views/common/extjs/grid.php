<?php

xView::load('common/extjs/base');

class CommonExtjsGridView extends CommonExtjsBaseView {

    function init() {
        // Setups includes
        $this->add_meta(array(
            'js' => array(
                xUtil::url('a/js/ext-ux/native/RowEditor.js'),
                xUtil::url('a/js/ext-ux/native/CheckColumn.js'),
                xUtil::url('a/js/ext-ux/form/XDateField.js')
            ),
            'css' => array(
                xUtil::url('a/js/ext-ux/ux/css/RowEditor.css')
            )
        ));
        // Setups default data values
        $this->defaults(array(
            'title' => 'Elements',
            'id' => sha1(microtime().session_id())
        ));
        // Checks madatory data values
        if (!@$this->data['url']) throw new xException('url data missing');
        if (!@$this->data['fields']) throw new xException('fields data missing: mandatory for Ext.data.Reader');
        if (!@$this->data['columns']) throw new xException('columns data missing: mandatory for Ext.grid.GridPanel');
    }
}