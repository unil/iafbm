<?php
class EvaluationsRapportsController extends iaExtRestController {
    
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