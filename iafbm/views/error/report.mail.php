<?php

/**
 * @package iafbm
 * @subpackage view
 */
class ErrorReportMailView extends xView {

    function render() {
        return $this->apply('report.mail.tpl');
    }
}

?>
