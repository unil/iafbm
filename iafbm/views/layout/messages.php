<?php

/**
 * @package iafbm
 * @subpackage view
 */
class LayoutMessagesView extends xView {

    /**
     * Retrives messages queue from xWebFront and applies the messages template.
     */
    function render() {
        $this->add_data(array('messages' => xWebFront::messages()));
        return $this->apply('messages.tpl');
    }
}

?>
