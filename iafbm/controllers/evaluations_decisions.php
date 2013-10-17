<?php
require_once('evaluations.php');

class EvaluationsDecisionsController extends AbstractEvaluationController {
    
    public $model = 'evaluation_decision';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>