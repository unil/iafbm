<?php
require_once('evaluations.php');

class EvaluationsCdirsController extends AbstractEvaluationController {
    
    public $model = 'evaluation_cdir';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function EvaluationAction() {
        $data['id'] = 1;
        return xView::load('evaluations/detail', $data)->render();
    }
    
    function post(){
        $this->check_closed();
        parent::post();
    }

}
?>