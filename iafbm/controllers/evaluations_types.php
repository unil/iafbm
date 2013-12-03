<?php
require_once('evaluations.php');

class EvaluationsTypesController extends AbstractEvaluationController {
    
    public $model = 'evaluation_type';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
}
?>