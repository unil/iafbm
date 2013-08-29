<?php
require_once('evaluations.php');

class EvaluationsApercusController extends AbstractEvaluationController {
    
    public $model = 'evaluation_apercu';
    
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