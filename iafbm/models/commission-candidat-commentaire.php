<?php

// This model name feels ugly
class CommissionCandidatCommentaireModel extends xModelMysql {

    var $table = 'commissions_candidats_commentaires';

    var $mapping = array(
        'id' => 'id',
        'created' => 'created',
        'modified' => 'modified',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
    );
}
