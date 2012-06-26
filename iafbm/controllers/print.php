<?php

class printController extends iaExtRestController {

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
        $this->params['xorientation'] = 'landscape';
        // Fetches related records
        $id = @$this->params['id'];
        if (!$id) throw new xException('Commission id parameter missing');
        $commission = xController::load('commissions', array(
            'id' => $id
        ))->get();
        if (!$commission) throw new xException('Commission does not exist');
        $membres = xController::load('commissions_membres', array(
            'commission_id' => $id,
            'xjoin' => 'personne, personne_denomination, commission_fonction',
            'xorder_by' => 'commission_fonction_position, personne_nom, personne_prenom'
        ))->get();
        // Transforms members structure (this feels dirty, sorry)
        $m = array();
        $fields_to_keep = array('id', 'personne_id', 'personne_denomination_abreviation', 'personne_nom', 'personne_prenom', 'commission_fonction_id', 'commission_fonction_nom', 'fonction_complement');
        $fields_to_concat = array('personne_denomination_abreviation', 'commission_fonction_nom', 'fonction_complement');
        foreach ($membres['items'] as $membre) {
            $membre = xUtil::filter_keys($membre, $fields_to_keep);
            $id = $membre['personne_id'];
            if (@!$m[$id]) {
                $m[$id] = $membre;
                foreach($fields_to_concat as $field) {
                    $m[$id][$field] = array($membre[$field]);
                }
            } else {
                foreach($fields_to_concat as $field) {
                    $m[$id][$field] = array_merge($m[$id][$field], array($membre[$field]));
                }
            }
        }
        // Renders view
        $data = array(
            'commission' => array_shift($commission['items']),
            'membres' => $m
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
        // PDF formatting parameters
        $orientation = @$this->params['xorientation'];
        if (!$orientation) $orientation = 'portrait';
        $paper = @$this->params['xpaper'];
        $paper = strtolower($paper);
        if (!$paper) $paper = 'a4';
        // Renders $html within print template
        $html = xView::load('layout/print', array(
            'content' => $html
        ))->render();
        // Returns HTML if required
        if (isset($this->params['html'])) die($html);
        // Creates PDF file
        require_once(xContext::$basepath.'/lib/dompdf/dompdf_config.inc.php');
        $dompdf = new DOMPDF();
        $dompdf->set_paper($paper, $orientation);
        $dompdf->set_base_path(xContext::$baseurl);
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream("print.pdf");
        exit();
    }
}
