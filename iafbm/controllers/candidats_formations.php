<?php

class CandidatsFormationsController extends iaWebController {

    var $model = 'candidat_formation';

    var $sort_fields_substitutions = array(
        'formation_id' => array(
            'field' => 'formation_abreviation',
            'join' => 'formation'
        )
    );
}