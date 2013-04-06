<?php

class CommissionFinalisationModel extends iaModelMysql {

    var $table = 'commissions_finalisations';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'commission_id' => 'commission_id',
        'candidat_id' => 'candidat_id',
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

    var $archive_foreign_models = array(
        'candidat' => array('candidat_id' => 'id')
    );

    // Self-documentation
    var $description = 'phase de finalisations des commissions';
    var $labels = array(
        'id' => 'identifiant interne',
        'actif' => 'enregistrement actif',
        'commission_id' => 'identifiant de la commission',
        'candidat_id' => 'identifiant du candidat retenu',
        'termine' => 'phase terminée',
        'reception_contrat_date' => 'date de réception du contrat',
        'reception_contrat_etat' => 'etat de réception du contrat (obsolète)',
        'reception_contrat_commentaire' => 'reception_contrat_commentaire',
        'debut_activite' => 'date de début d\'activité',
        'commentaire' => 'commentaire'
    );
}
