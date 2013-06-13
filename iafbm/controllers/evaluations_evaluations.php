<?php
require_once('evaluations.php');

class EvaluationsEvaluationsController extends AbstractEvaluationController {
    
    public $model = 'evaluation_evaluation';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>