<?php
class EvaluationsMembresController extends iaExtRestController {
    
    public $model = 'evaluation_membre';
    
    function get() {
        $r = parent::get();
        foreach ($r['items'] as &$record){
            
            
            $evaluateurs = xModel::load('evaluation_evaluateur', array('evaluation_id' => 1))->get();
            foreach($evaluateurs as $e) $tabEvaluateurs[] = $e['personne_prenom'].' '.$e['personne_nom'];
            $record['_evaluateurs'] = implode(", ", $tabEvaluateurs);
        }
        return $r;
    }
    
    function indexAction() {
        return $this->EvaluationsMembresAction();
    }
    
    /*function EvaluationsMembresAction() {
        $data['id'] = 1;
        
        return xView::load('evaluations/detail', $data)->render();
    }*/

}
?>