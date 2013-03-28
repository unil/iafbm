<?php
class EvaluationsEvaluateursController extends iaExtRestController {
    
    public $model = 'evaluation_evaluateur';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>