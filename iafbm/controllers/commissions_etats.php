<?php

require_once('commissions.php');

/**
 * @package iafbm
 * @subpackage controller
 */
class CommissionsEtatsController extends AbstractCommissionController {
    var $model = 'commission_etat';
    var $allow = array('get');
}