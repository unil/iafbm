<?php
class EvaluationsContratsController extends iaExtRestController {
    
    public $model = 'evaluation_contrat';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>