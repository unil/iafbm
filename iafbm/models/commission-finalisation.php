<?php

class CommissionFinalisationModel extends xModelMysql {

    var $table = 'commissions_finalisations';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'reception_contrat_date' => 'reception_contrat_date',
        'reception_contrat_etat' => 'reception_contrat_etat',
        'reception_contrat_commentaire' => 'reception_contrat_commentaire',
        'debut_activite' => 'debut_activite',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
        'commission_id' => 'mandatory'
    );
}
