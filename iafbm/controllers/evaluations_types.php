<?php
class EvaluationsTypesController extends iaExtRestController {
    
    public $model = 'evaluation_type';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
}
?>