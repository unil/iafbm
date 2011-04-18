<?php

class LayoutLayoutView extends xView {

    function init() {
        $this->meta = xUtil::array_merge($this->meta, array(
            'js' => array(
                error_reporting() ?
                    xUtil::url('a/js/ext/adapter/ext/ext-base-debug.js') :
                    xUtil::url('a/js/ext/adapter/ext/ext-base.js'),
                error_reporting() ?
                    xUtil::url('a/js/ext/ext-all-debug.js') :
                    xUtil::url('a/js/ext/ext-all.js'),
                xUtil::url('a/js/ext-custom/date.i18n.js')
                //xUtil::url('a/js/xfreemwork/lib/core.js')
            ),
            'layout' => array(
                'template' => 'layout.tpl',
                // Possible types: normal, half, full
                // see main.css
                'type' => 'full'
            ),
            'css' => array(
                xUtil::url('a/js/ext/resources/css/ext-all.css'),
                //xUtil::url('a/js/ext/resources/css/xtheme-gray.css'),
                xUtil::url('a/css/main.css'),
            )
        ));
    }

    function render() {
        if (@$this->meta['related']['additional']) $this->meta['layout']['type'] = 'normal';
        return $this->apply($this->meta['layout']['template']);
    }
}