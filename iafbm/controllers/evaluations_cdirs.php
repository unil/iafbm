<?php
class EvaluationsCdirsController extends iaExtRestController {
    
    public $model = 'evaluation_cdir';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }

}
?>