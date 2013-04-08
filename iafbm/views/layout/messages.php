<?php

/**
 * @package iafbm
 * @subpackage view
 */
class LayoutMessagesView extends xView {

    function render() {
        $this->add_data(array('messages' => xWebFront::messages()));
        return $this->apply('messages.tpl');
    }
}

?>
