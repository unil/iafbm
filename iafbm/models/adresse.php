<?php

class AdresseModel extends iaModelMysql {

    var $table = 'adresses';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'adresse_type_id' => 'adresse_type_id',
        'rue' => 'rue',
        'npa' => 'npa',
        'lieu' => 'lieu',
        'pays_id' => 'pays_id',
        'geo_x' => 'geo_x',
        'geo_y' => 'geo_y'
    );

    var $primary = array('id');

    var $joins = array(
        'adresse_type' => 'LEFT JOIN adresses_types ON (adresses.adresse_type_id = adresses_types.id)',
        'pays' => 'LEFT JOIN pays ON (adresses.pays_id = pays.id)'
    );

    var $validation = array();

    var $archive_foreign_models = array(
        'adresse_type' => array('adresse_type_id' => 'id')
    );

    /**
     * Sets 'geo_x' and 'geo_y' parameters values with geocoded coordinates.
     * If geocoding fails, sets values to null.
     *
     * Uses Google Maps geocoding API.
     * @see https://developers.google.com/maps/documentation/javascript/geocoding
     * @see https://developers.google.com/maps/documentation/geocoding/index
     */
    protected function geocode() {
        // Creates the merged record values used for geocoding,
        // merging the stored record (if any) with given fields values in parameters
        $record = !@$this->params['id'] ?
            array() :
            xModel::load('adresse', array('id' => $this->params['id'], 'xjoin' => null))->get(0);
        $record = array_merge(
            $record,
            $this->fields_values()
        );
        if ($record['pays_id']) {
            // Adds pays information (if applicable)
            $pays = xModel::load('pays', array('id' => $record['pays_id']))->get(0);
            if ($pays) $record['pays_code'] = $pays['code'];
        }
        // Checks that required fields for geocoding are not empty
        $required_fields = xUtil::filter_keys($record, array('rue', 'npa', 'lieu'));
        if (!min($required_fields)) return $this->params['geo_x'] = $this->params['geo_y'] = null;
        // Url & parameters
        $url = 'http://maps.googleapis.com/maps/api/geocode/json';
        $address = implode(', ', array(
            $record['rue'],
            $record['npa'],
            $record['lieu']
        ));
        $query = http_build_query(array(
            'sensor' => 'false',
            'language' => 'fr',
            'address' => $address
        ));
        // Service call
        $result = file_get_contents("$url?$query");
        if (!$result) return $this->params['geo_x'] = $this->params['geo_y'] = null;
        $info = json_decode($result);
        if ($info->status != 'OK') return $this->params['geo_x'] = $this->params['geo_y'] = null;
        if (!count($info->results)) return $this->params['geo_x'] = $this->params['geo_y'] = null;
        $point = array_shift($info->results)->geometry->location;
        if (!$point) return $this->params['geo_x'] = $this->params['geo_y'] = null;
        $this->params['geo_x'] = $point->lng;
        $this->params['geo_y'] = $point->lat;
    }

    function put() {
        // Geocodes address
        $this->geocode();
        // Stores record
        return parent::put();
    }

    function post() {
        // Geocodes address
        $this->geocode();
        // Stores record
        return parent::post();
    }

}
