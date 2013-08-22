<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class ErrorController extends xWebController {

    function error_contents() {
        // To be overriden
        return null;
    }

    /**
     * Displays error page.
     */
    function defaultAction() {
        // Error message display
        $html = xView::load('error/display', $this->params, $this->meta)->render();
        xWebFront::messages($html, 'error');
        // Save exception in session for optional report action
        $this->session('exception', $this->params['exception']);
        return $this->error_contents();
    }

    /**
     * Allows the user to automagicaly report an error.
     * This is not used.
     */
    function reportAction() {
        $this->meta = xUtil::array_merge($this->meta, array(
            'title' => _('Report error')
        ));
        $exception = $this->session('exception');
        if ($exception) {
            $report = xView::load('error/report.mail', array('items' => array(
                'exception' => $exception,
                'username' => xContext::$auth->username(),
                'history' => xWebFront::$history
            )))->render();
            $mail_sent = mail(
                xContext::$config->site->mail->webmaster->mail,
                'Error report',
                $report,
                "From: ".xContext::$config->site->mail->noreply->name."<".xContext::$config->site->mail->noreply->mail.">"
            );
        }
        // Reset session exception
        $this->session('exception', null);
        // Setups user message
        if ($mail_sent) xWebFront::messages(_('Error details have been sent to our team'), 'ok');
        else xWebFront::messages(_('Error details could not be sent to our team :('), 'error');
        return xView::load('error/report')->render();
    }
}

?>
