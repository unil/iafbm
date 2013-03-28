<?php
class EvaluationsPreavisController extends iaExtRestController {
    
    public $model = 'evaluation_preavis';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
}
?>