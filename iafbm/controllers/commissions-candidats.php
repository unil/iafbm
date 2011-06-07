<?php

class CommissionsCandidatsController extends iaWebController {
    var $model = 'commission-candidat';
    // FIXME: a bug in xModel::foreign_fields_values()
    // leads to an Exception: the generated dbfield is incorrect
    var $query_exclude_fields = array('commission_commission-type_id');
}