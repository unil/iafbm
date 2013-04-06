<?php

// This model name feels ugly
class CommissionCandidatCommentaireModel extends iaModelMysql {

    var $table = 'commissions_candidats_commentaires';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'termine' => 'termine',
        'commentaire' => 'commentaire'
    );

    var $primary = array('id');

    var $validation = array(
    );

    // Self-documentation
    var $description = 'commentaires liés aux candidats des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de commission',
        'termine' => 'phase terminée',
        'commentaire' => 'commentaire'
    );
}
