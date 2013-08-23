<?php
/**
 * This abstract controller should be extended
 * by every controller that relates to a evaluation.
 *
 * It implements:
 * - a check that prevents a closed evaluation to be modified
*/
abstract class AbstractEvaluationController extends iaExtRestController {

    function post() {
        $this->check_closed();
        return parent::post();
    }

    /**
     * Prevents modifications if evaluation status is 'closed'
     * by throwing an exection if the given evaluation id is closed
     */
    protected function check_closed() {
        // Merges item data with params with priority to params
        // to ensure 'evaluation_id' param exists if applicable
        $params = xUtil::array_merge(
            array('id' => $this->params['items']['id']),
            array('evaluation_id' => @$this->params['items']['evaluation_id']),
            $this->params
        );
        // Depending on child class using this method,
        // the 'id' or 'evaluation_id' parameter is to be used
        $id = @$params['evaluation_id'] ? @$params['evaluation_id'] : @$params['id'];
        if (!$id) throw new xException('Missing id parameter');
        $evaluation = xModel::load('evaluation', array(
            'id' => $id
        ))->get(0);
        if (!$evaluation) throw new xException("Evaluations does not exist (id: {$id})", 500, $params);
        if ($evaluation['evaluation_etat_id'] == 3) {
            throw new xException('Cannot modify a closed evaluation', 403, $evaluation);
        }
    }
}


class EvaluationsController extends AbstractEvaluationController {
    
    public $model = 'evaluation';
    
    function indexAction() {
        $data = array(
            'title' => 'Gestion des évaluations',
            'id' => 'évaluations',
            'model' => 'Evaluation',
            'columns' => 'iafbm.columns.Evaluation',
            'store-params' => array('actif' => 1)
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function detailAction() {
        $id = @$this->params['id'];
        if (!$id) throw new xException("Le numéro d'évaluation fourni n'est pas valide", 400);
        $evaluation = xModel::load('evaluation', array('id'=>$id))->get(0);
        if (!$evaluation) throw new xException("L'évaluaion demandée est introuvable", 404);
        return xView::load('evaluations/detail', $evaluation, $this->meta)->render();
    }
    
    /**
     * Add ghosts fields
     */
    function get() {
        $evaluations = parent::get();
        foreach ($evaluations['items'] as &$evaluation) {
            //add '_prenom_nom' ghost field
            $evaluation['_prenom_nom'] = $evaluation['personne_prenom'].' '.$evaluation['personne_nom'];
            
            
            //add '_evaluateurs' ghost field
            $evaluateurs = xModel::load('evaluation_evaluateur', array('evaluation_id' => $evaluation['id']))->get();
            foreach($evaluateurs as $e) $tabEvaluateurs[] = $e['personne_prenom'].' '.$e['personne_nom'];
            $evaluation['_evaluateurs'] = @implode(", ", $tabEvaluateurs);
            unset($tabEvaluateurs);
            
            //add '_mandat' ghost field
            $personne_activite = xModel::load('personne_activite', array('personne_id' => $evaluation['personne_id'], 'activite_id' => $evaluation['activite_id']))->get();
            $evaluation['_mandat'] = !@$personne_activite[0]['debut'] ? '-' : date("d.m.Y", strtotime(@$personne_activite[0]['debut'])).' - '.date("d.m.Y", strtotime(@$personne_activite[0]['fin']));
        }
        
        return $evaluations;
    }
    
    /**
     * Manages evaluation 'closed-lock' and archiving:
     * - Prevents from modifying a 'closed' evaluation
     * - Archives evaluation when evaluation_etat becomes 'closed'
     * @see AbstractCommissionController
     */
    function post() {
        $this->check_closed();
        // Actual commission modification
        $t = new xTransaction();
        $t->start();
        $result = parent::post();
        // Archives evaluation if state becomes 'closed'
        if (@$this->params['items']['evaluation_etat_id'] == 3) {
            xModel::load('evaluation', array(
                'id' => $this->params['id']
            ))->archive();
        }
        $t->end();
        // Returns operation result
        return $result;
    }
    
    function put() {
        if (isset($this->params['id'])) return $this->post();
        $params = $this->params['items'];
        $t = new xTransaction();
        $t->start();
        // Inserts the evaluation model
        $t->execute(xModel::load('evaluation', $params), 'put');
        $insertid = $t->insertid();
        // Inserts related items
        $items = array(
            xModel::load('evaluation_cdir', array('evaluation_id'=>$insertid)),
            xModel::load('evaluation_evaluation', array('evaluation_id'=>$insertid)),
            xModel::load('evaluation_contrat', array('evaluation_id'=>$insertid)),
        );
        foreach ($items as $item) $t->execute($item, 'put');
        $r = $t->end();
        $r['items'] = @array_shift(xModel::load('evaluation', array('id' => $insertid))->get());
        return $r;
    }
}
?>