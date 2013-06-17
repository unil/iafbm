<?php
require_once('evaluations.php');

class EvaluationsEvaluateursController extends AbstractEvaluationController {
    
    public $model = 'evaluation_evaluateur';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function post(){
        $this->check_closed();
        parent::post();
    }
    
    function delete(){
        $this->check_closed();
        parent::delete();
    }
    
    function put(){
        $this->check_closed();
        parent::put();
    }

}
?>