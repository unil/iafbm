<?php

class CountryModel extends xModelPostgres {

    var $table = 'availability';

    var $mapping = array(
        'id' => 'id',
        'name' => 'fk_profile',
        'created' => 'is_created',
        'modified' => 'is_modified',
        'deleted' => 'is_deleted',
        'begin' => 'begin',
        'end' => 'end',
        'zip' => 'zip',
        'location' => 'location',
        'distance' => 'distance',
    );

    var $validation = array(
        'begin' => array(
            'mandatory',
            'datetime'
        ),
        'end' => array(
            'mandatory',
            'datetime'
        ),
        'zip' => array(
            'mandatory',
            'integer',
            'minlength' => array('length'=>4),
            'maxlength' => array('length'=>4)
        ),
        'location' => array(
            'mandatory'
        ),
        'distance' => array(
            'mandatory',
            'integer',
            'minvalue' => array('length'=>0)
        ),
        'profile' => array(
            'mandatory',
            'integer'
        ),
    );

    var $primary = array('id');

    var $joins = array(
        'supplier' => 'LEFT JOIN profile_supplier ON (availability.fk_profile = profile_supplier.id)'
    );

    var $return = array('*');

    function put() {
        $this->params['location'] = $this->geocode();
        return parent::put();
    }

    function post() {
        $this->params['location'] = $this->geocode();
        return parent::post();
    }

    protected function geocode() {
        $g = new xGeocoderGeonamesZipSearch(array(
            'postalcode' => $this->params['zip'],
            'country' => 'CH',
            'maxRows' => '1'
        ));
        $r = $g->get();
        $r = @$r->postalCodes[0];
        if (!$r) throw new xException("Could not geocode zip code: {$this->params['zip']}", 500);
        return "POINT({$r->lat} {$r->lng})";
    }
}
