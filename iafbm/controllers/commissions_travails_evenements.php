<?php

require_once('commissions.php');

class CommissionsTravailsEvenementsController extends AbstractCommissionController {

    var $model = 'commission_travail_evenement';

    var $sort_fields_substitutions = array(
        'commission_travail_evenement_type_id' => array(
            'field' => 'commission_travail_evenement_type_nom',
            'join' => 'commission_travail_evenement_type'
        )
    );
}