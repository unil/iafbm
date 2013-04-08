<?php

/**
 * @package iafbm
 * @subpackage controller
 */
class ActivitesController extends iaExtRestController {

    var $model = 'activite';

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