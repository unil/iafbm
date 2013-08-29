<?php
require_once('evaluations.php');

class EvaluationsEtatsController extends AbstractEvaluationController {
    
    public $model = 'evaluation_etat';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>