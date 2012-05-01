<?php

class printController extends iaWebController {

    function defaultAction() {
        $controller = $this->params['controller'];
        $method = "print_{$controller}";
        if (!method_exists($this, $method)) throw new xException("This entity is not printable ({$controller})", 404);
        return $this->$method();
    }

    protected function print_commissions() {
        $commissions = xController::load('commissions', array(
            'xjoin' => 'commission_type,commission_etat,section'
        ))->get();
        $data = $commissions['items'];
        $html = xView::load('print/commissions', $data)->render();
        return $this->_print($html);
    }

    protected function print_commissions_membres() {
        $id = @$this->params['id'];
        if (!$id) throw new xException('Commission id parameter missing');
        $commission = xController::load('commissions', array(
            'id' => $id
        ))->get();
        if (!$commission) throw new xException('Commission does not exist');
        $membres = xController::load('commissions_membres', array(
            'commission_id' => $id,
            'xjoin' => 'personne,personne_denomination,commission_fonction',
            'xorder_by' => 'commission_fonction_position, personne_nom, personne_prenom'
        ))->get();
        $data = array(
            'commission' => array_shift($commission['items']),
            'membres' => $membres['items']
        );
        $html = xView::load('print/membres-commission', $data)->render();
        return $this->_print($html);
    }

    protected function print_candidats() {
        $id = @$this->params['id'];
        if (!$id) throw new xException('Commission id parameter missing');
        $commission = xController::load('commissions', array(
            'id' => $id
        ))->get();
        if (!$commission) throw new xException('Commission does not exist');
        $candidats = xController::load('candidats', array(
            'commission_id' => $id
        ))->get();
        $data = array(
            'commission' => array_shift($commission['items']),
            'candidats' => $candidats['items']
        );
        $html = xView::load('print/candidats-commission', $data)->render();
        return $this->_print($html);
    }

    function _print($html) {
        // Renders $html within print template
        $html = xView::load('layout/print', array(
            'content' => $html
        ))->render();
        // Returns HTML if required
        if (isset($this->params['html'])) die($html);
        // Creates PDF file
        require_once(xContext::$basepath.'/lib/dompdf/dompdf_config.inc.php');
        $dompdf = new DOMPDF();
        $dompdf->set_paper('a4', 'portrait');
        $dompdf->set_base_path(xContext::$baseurl);
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream("print.pdf");
        exit();
    }
}
