<?php

class ErrorController extends xWebController {

    function error_contents() {
        // To be overriden
        return null;
    }

    function defaultAction() {
        $html = xView::load('error/display', $this->params)->render();
        // 404 Not found error processing
        if (@$this->params['exception']->status == 404) {
            $this->add_meta(array('title' => _('Page not found')));
            xWebFront::messages($html, 'error');
        }
        // Default error processing
        $this->meta = xUtil::array_merge($this->meta, array(
            'title' => _('Error')
        ));
        // Save exception in session for
        $this->session('exception', $this->params['exception']);
        return $this->error_contents();
    }

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
            mail(
                xContext::$config->site->mail->webmaster->mail,
                'Okikoo error report',
                $report,
                "From: ".xContext::$config->site->mail->noreply->name."<".xContext::$config->site->mail->noreply->mail.">"
            );
        }
        // Reset session exception
        $this->session('exception', null);
        return xView::load('error/report')->render();
    }
}

?>
