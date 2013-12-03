<?php
require_once('evaluations.php');

class EvaluationsRapportsController extends AbstractEvaluationController {
    
    public $model = 'evaluation_rapport';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>