<?php

class PersonnesAdressesController extends iaExtRestController {

    var $model = 'personne_adresse';

    var $query_fields = array('adresse_rue', 'adresse_npa', 'adresse_lieu');

    var $sort_fields_substitutions = array(
        'adresse_adresse_type_id' => array(
            'field' => 'adresse_type_nom',
            'join' => 'adresse,adresse_type'
        ),
        'adresse_pays_id' => array(
            'field' => 'pays_nom',
            'join' => 'adresse,pays'
        )
    );

    function exportAction() {
        $filename = 'export-adresses.csv';
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename={$filename}");
        print xFront::load('api', array(
            'xformat' => 'csv',
            'xmode' => @$this->params['mode']
        ))->encode($this->export());
        exit();
    }

    function export() {
        $data = xModel::load('personne_adresse', array(
            'xjoin' => 'personne,adresse'
        ))->get();
        return $data;
    }

    function post() {
        $params = $this->params['items'];
        $personne_adresse = xModel::load($this->model, $params);
        $adresse = xModel::load('adresse', $personne_adresse->foreign_fields_values('adresse'));
        $t = new xTransaction();
        $t->start();
        // Make defaut unique
        $this->_unique_defaut(array(
            'id' => $params['id'],
            'defaut' => @$params['defaut']
        ), $t);
        if ($personne_adresse->params) $t->execute($personne_adresse, 'post');
        if ($adresse->params) $t->execute($adresse, 'post');
        // Finishes transaction
        $r = $t->end();
        $r['items'] = array_shift(xModel::load($this->model, array('id' => $params['id']))->get());
        return $r;
    }

    function put() {
        $params = $this->params['items'];
        $personne_adresse = xModel::load($this->model, $params);
        $adresse = xModel::load('adresse', $personne_adresse->foreign_fields_values('adresse'));
        $t = new xTransaction();
        $t->start();
        $t->execute($adresse, 'put');
        $personne_adresse->params['adresse_id'] = $t->insertid();
        $t->execute($personne_adresse, 'put');
        // Make defaut unique
        $this->_unique_defaut(array(
            'id' => $t->insertid(),
            'defaut' => @$params['defaut']
        ), $t);
        // Finishes transaction
        $r = $t->end();
        $r['items'] = array_shift(xModel::load($this->model, array('id' => $t->insertid()))->get());
        return $r;
    }

    /**
     * If $params['defaut'] is true,
     * updates all Adresse rows related to this personne_id,
     * setting their 'defaut' field to false.
     * @todo Factorize this between personnes_adresses, personnes_emails, personnes_telephones
     * @see PersonnesAdressesController::_unique_defaut()
     * @see PersonnesEmailsController::_unique_defaut()
     * @see PersonnesTelephonesController::_unique_defaut()
     */
    protected function _unique_defaut($params, $transaction) {
        // Parameters check
        if (@!$params['defaut']) return; // If default is not set to true, no need to continue
        if (@!$params['id']) throw new xException('id parameter missing');
        // Retrieves personne_id from row id
        $r = xModel::load($this->model, array(
            'id'=>$params['id']
        ))->get(0);
        $personne_id = $r['personne_id'];
        if (@!$personne_id) throw new xException('Error retrieving personne_id');
        // Retrieves rows with 'default' to be set to null
        $rows = xModel::load($this->model, array(
            'personne_id' => $personne_id,
            'id' => $params['id'],
            'id_comparator' => '!='
        ))->get();
        // Sets rows 'defaut' to null
        foreach ($rows as $row) {
            $model = xModel::load($this->model, array(
                'id' => $row['id'],
                'defaut' => null
            ));
            $transaction->execute($model, 'post');
        }
    }

    function delete() {
        $params = $this->params;
        $personne_adresse = array_shift(xModel::load($this->model, array('id'=>$params['id']))->get());
        $adresse_id = $personne_adresse['adresse_id'];
        $t = new xTransaction();
        $t->start();
        $t->execute(xModel::load($this->model, $params), '_delete_soft');
        $t->execute(xModel::load('adresse', array('id'=>$adresse_id)), '_delete_soft');
        $r = $t->end();
        $r['items']['id'] = $params['id'];
        return $r;
    }
}