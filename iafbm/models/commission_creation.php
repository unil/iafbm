<?php

class CommissionCreationModel extends iaModelMysql {

    var $table = 'commissions_creations';

    var $mapping = array(
        'id' => 'id',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'decision' => 'date_decision',
        'preavis' => 'date_preavis',
        'autorisation' => 'date_autorisation',
        'annonce' => 'date_annonce',
        'composition' => 'date_composition',
        'composition_validation' => 'date_composition_validation',
        'commentaire' => 'commentaire',
        'actif' => 'actif',
        'created' => 'created',
        'modified' => 'modified'
    );

    var $primary = array('id');

    var $joins = array(
        'commission' => 'LEFT JOIN commissions ON (commissions_creations.commission_id = commissions.id)'
    );

    var $join = 'commission';

    var $validation = array();
}
