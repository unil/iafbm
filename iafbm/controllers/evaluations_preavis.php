<?php
require_once('evaluations.php');

class EvaluationsPreavisController extends AbstractEvaluationController {
    
    public $model = 'evaluation_preavis';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
}
?>