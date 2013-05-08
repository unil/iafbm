<?php

// This model name feels ugly
class CommissionCandidatCommentaireModel extends iaModelMysql {

    var $table = 'commissions_candidats_commentaires';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'date_cloture' => 'date_cloture',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
    );
}
