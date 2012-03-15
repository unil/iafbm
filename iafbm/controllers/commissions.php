<?php

class CommissionsController extends iaWebController {

    var $model = 'commission';

    var $query_fields = array('nom');

    var $sort_fields_substitutions = array(
        'commission_type_id' => array(
            'field' => 'commission_type_nom',
            'join' => 'commission_type'
        ),
        'section_id' => array(
            'field' => 'section_code',
            'join' => 'section'
        ),
        'commission_etat_id' => 'commission_etat_id'
    );

    function indexAction() {
        $data = array(
            'title' => 'Gestion des commissions',
            'id' => 'commissions',
            'model' => 'Commission'
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }

    function detailAction() {
        $id = @$this->params['id'];
        if (!$id) throw new xException("Le numéro de commission fourni n'est pas valide", 400);
        $commission = xModel::load('commission', array('id'=>$id))->get(0);
        if (!$commission) throw new xException("La commission demandée est introuvable", 404);
        return xView::load('commissions/detail', $commission, $this->meta)->render();
    }

    function get() {
        $commissions = parent::get();
        // Adds '_president' ghost field (if applicable)
        $return = xModel::load($this->model, $this->params)->return;
        if (xUtil::in_array(array('*', '_president'), $return)) {
            foreach ($commissions['items'] as &$commission) {
                $president = array_shift(xModel::load(
                    'commission_membre',
                    array(
                        'commission_id' => $commission['id'],
                        'commission_fonction_id' => 1,
                        'xjoin' => 'personne'
                    )
                )->get());
                $commission['_president'] = ($president) ?
                    "{$president['personne_prenom']} {$president['personne_nom']}" :
                    '-';
            }
        }
        return $commissions;
    }

    /**
     * Prevents from modifying a 'closed' commission
     */
    function post() {
        // Prevents modifications if commission status is 'closed'
        $commission = xModel::load('commission', array(
            'id' => $this->params['id']
        ))->get(0);
        if ($commission['commission_etat_id'] == 3) {
            throw new xException('Cannot modify a closed commission', 403);
        }
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
        switch (@$params['commission_type_id']) {
            case 1:
                $items = array(
                    xModel::load('commission_creation', array('commission_id'=>$insertid)),
                    xModel::load('commission_candidat_commentaire', array('commission_id'=>$insertid)),
                    xModel::load('commission_travail', array('commission_id'=>$insertid)),
                    xModel::load('commission_validation', array('commission_id'=>$insertid)),
                    xModel::load('commission_finalisation', array('commission_id'=>$insertid))
                );
                break;
            default:
                throw new xException('Unknown commission type', 500);
        }
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