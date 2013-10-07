<?php
require_once('evaluations.php');

class EvaluationsEvaluateursController extends AbstractEvaluationController {
    
    public $model = 'evaluation_evaluateur';
    
    function indexAction() {
        return $this->EvaluationAction();
    }
    
    function post(){
        $this->check_closed();
        return parent::post();
    }
    
    function delete(){
        $this->check_closed();
        return parent::delete();
    }
    
    function put(){
        $this->check_closed();
        
        $params = array(
            'evaluation_id' => $this->params['evaluation_id']
        );
        $evaluators = xModel::load('evaluation_evaluateur', $params)->get();
        
        // Check if the given evaluator is already in the list
        foreach($evaluators as $e){
            if($e['personne_id'] == $this->params['items']['personne_id']){
                $exists = true;
            }
        }
        if(@$exists) throw new xException(500);
        
        
        return parent::put();
    }

}
?>