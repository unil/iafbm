<?php
class EvaluationsEvaluationsController extends iaExtRestController {
    
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