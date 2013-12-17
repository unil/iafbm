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
        if (@$this->params['items']['evaluation_evaluation_etat_id'] == 0) {
            xModel::load('evaluation', array(
                'id' => $this->params['id']
            ))->archive();
            //Change the etat_id of the evaluation
            $t->execute(xModel::load('evaluation', array(
                    'id' => $this->params['id'],
                    'evaluation_etat_id' => 4)
            ), 'post');
        }
        $t->end();
        // Returns operation result
        return $result;
    }

}
?>