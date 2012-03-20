<?php

require_once('commissions.php');

class CommissionsEtatsController extends AbstractCommissionController {
    var $model = 'commission_etat';
    var $allow = array('get');
}