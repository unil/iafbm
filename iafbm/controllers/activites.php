<?php

class ActivitesController extends iaWebController {

    var $model = 'activite';

    var $query_fields = array(
        'activite_nom_nom', 'activite_nom_abreviation', 'activite_type_nom', 'section_code'
    );
    var $query_transform = array(
        'date_naissance' => 'date,date-binomial'
    );
    var $query_join = 'commission';

    var $sort_fields_substitutions = array(
        'activite_nom_id' => array(
            'field' => 'activite_nom_nom',
            'join' => 'activite_nom'
        ),
        'section_id' => array(
            'field' => 'section_nom',
            'join' => 'section'
        ),
        'activite_type_id' => array(
            'field' => 'activite_type_nom',
            'join' => 'activite_type'
        )
    );

    function indexAction() {
        $data = array(
            'title' => 'Activités professionnelles',
            'id' => 'activites',
            'model' => 'Activite',
//            'editable' => false,
//            'toolbarButtons' => array('search')
        );
        return xView::load('common/extjs/grid', $data, $this->meta)->render();
    }
}