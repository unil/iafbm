<?php
class EvaluationsRapportController extends iaExtRestController {

    var $model = 'evaluation_rapport';
    
    function indexAction() {
        return $this->EvaluationsRapportAction();
    }
    
    function EvaluationsRapportAction() {
        $data['id'] = 2;
        return xView::load('evaluations/detail', $data)->render();
    }
    
    function get() {
        $evaluations = parent::get();
        return $evaluations;
    }

    /**
     * Manages commission 'closed-lock' and archiving:
     * - Prevents from modifying a 'closed' commission
     * - Archives commission when commission_etat becomes 'closed'
     * @see AbstractCommissionController
     */
    function post() {
        //$this->check_closed();
        // Actual commission modification
        $t = new xTransaction();
        $t->start();
        $result = parent::post();
        // Archives commission if state becomes 'closed'
        if (@$this->params['items']['commission_etat_id'] == 3) {
            xModel::load('commission', array(
                'id' => $this->params['id']
            ))->archive();
        }
        $t->end();
        // Returns operation result
        return $result;
    }

    /**
     * Depending on the type of the commission,
     * different types of database entities have to be created.
     */
    function put() {
        if (isset($this->params['id'])) return $this->post();
        $params = $this->params['items'];
        $t = new xTransaction();
        $t->start();
        // Inserts the commission model
        $t->execute(xModel::load('commission', $params), 'put');
        $insertid = $t->insertid();
        // Inserts related items
        $items = array(
            xModel::load('commission_creation', array('commission_id'=>$insertid)),
            xModel::load('commission_candidat_commentaire', array('commission_id'=>$insertid)),
            xModel::load('commission_travail', array('commission_id'=>$insertid)),
            xModel::load('commission_validation', array('commission_id'=>$insertid)),
            xModel::load('commission_finalisation', array('commission_id'=>$insertid))
        );
        foreach ($items as $item) $t->execute($item, 'put');
        $r = $t->end();
        $r['items'] = array_shift(xModel::load('commission', array('id' => $insertid))->get());
        return $r;
    }

    function delete() {
        if (!in_array('delete', $this->allow)) throw new xException("Method not allowed", 403);
        $t = new xTransaction();
        $t->start();
        $params = array('commission_id' => $this->params['id']);
        $t->execute(xModel::load('commission_finalisation', $params), 'delete');
        $t->execute(xModel::load('commission_validation', $params), 'delete');
        $t->execute(xModel::load('commission_travail', $params), 'delete');
        $t->execute(xModel::load('commission_creation', $params), 'delete');
        $t->execute(xModel::load('commission_membre', $params), 'delete');
        $t->execute(xModel::load('commission_candidat_commentaire', $params), 'delete');
        $t->execute(xModel::load('candidat', $params), 'delete');
        $t->execute(xModel::load('commission', $this->params), 'delete');
        return $t->end();
    }

}
?>