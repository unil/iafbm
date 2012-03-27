<?php

class CommissionMembreModel extends iaModelMysql {

    var $table = 'commissions_membres';

    var $mapping = array(
        'id' => 'id',
        'actif' => 'actif',
        'personne_id' => 'personne_id',
        'commission_id' => 'commission_id',
        'commission_fonction_id' => 'commission_fonction_id',
        'activite_id' => 'activite_id',
        'rattachement_id' => 'rattachement_id',
        'version_id' => 'version_id'
    );

    var $primary = array('id');

    var $joins = array(
        'personne' => 'LEFT JOIN personnes ON (commissions_membres.personne_id = personnes.id)',
        'commission_fonction' => 'LEFT JOIN commissions_fonctions ON (commissions_membres.commission_fonction_id = commissions_fonctions.id)',
        'activite' => 'LEFT JOIN activites ON (commissions_membres.activite_id = activites.id)',
        'activite_nom' => 'LEFT JOIN activites_noms ON (activites.activite_nom_id = activites_noms.id)',
        'rattachement' => 'LEFT JOIN rattachements ON (commissions_membres.rattachement_id = rattachements.id)',
        'commission' => 'LEFT JOIN commissions ON (commissions_membres.commission_id = commissions.id)',
        'commission_type' => 'LEFT JOIN commissions_types ON (commissions.commission_type_id = commissions_types.id)',
        'commission_etat' => 'LEFT JOIN commissions_etats ON (commissions.commission_etat_id = commissions_etats.id)',
        'section' => 'LEFT JOIN sections ON (commissions.section_id = sections.id)'
    );

    var $join = array('personne', 'commission', 'commission_fonction', 'activite', 'rattachement');

    var $wheres = array(
        'query' => "{{personne_id}} = {personne_id} AND commissions_membres.actif = 1 AND (1=0 [OR {{*}} LIKE {*}])"
    );

    var $validation = array(
        'version_id' => 'mandatory'
    );

    var $archive_foreign_models = array(
        'personne' => array('personne_id' => 'id'),
        'commission_fonction' => array('commission_fonction_id' => 'id'),
        'activite' => array('activite_id' => 'id'),
        'rattachement' => array('rattachement_id' => 'id')
    );

    function put() {
        $t = new xTransaction();
        $t->start();
        // Stores the actual record,
        // with a temporary version_id if parameter is present but null
        if (array_key_exists('version_id', $this->params) && is_null($this->params['version_id'])) {
            $this->params['version_id'] = xModel::load('version')->current();
        }
        $result = parent::put();
        // Stores current version to stick to this version of 'personne'
        // Updates 'version_id' off versioning
        $insert_id = $result['xinsertid'];
        if (!$insert_id) throw new xException("Problem retrieving insert id", 500);
        $version_id = xModel::load('version')->current();
        $model = xModel::load($this->name, array(
            'id' => $insert_id,
            'version_id' => $version_id
        ));
        $model->versioning = false;
        $model->post();
        // Simulates that 'version_id' was set with the actual PUT operation,
        // Replacing the temporary 'version_id' field old/new values
        // with the updated 'version_id'
        $r = xModel::load('version_data', array(
            'version_id' => $version_id,
            'field_name' => 'version_id'
        ))->get();
        foreach ($r as $rr) xModel::load('version_data', array(
            'id' => $rr['id']
        ))->delete();
        xModel::load('version_data', array(
            'version_id' => $version_id,
            'field_name' => 'version_id',
            'old_value' => null,
            'new_value' => $version_id
        ))->put();
        $t->end();
        return $result;
    }

    function post() {
        // Updates version to record latest version
        // if 'version_id' parameter is present but null
        // (e.g. the user clicked on the refresh button in commission_membre grid)
        if (array_key_exists('version_id', $this->params) && is_null($this->params['version_id'])) {
            // Retrieves this record 'personne_id' field
            // if not given through parameters
            $personne_id = @$this->params['personne_id'];
            if (!$personne_id) {
                $r = xModel::load($this->name, array(
                    'id' => $this->params['id']
                ))->get(0);
                $personne_id = @$r['personne_id'];
                if (!$personne_id) throw new xException('Error retrieving record personne_id');
            }
            // Retrieves the lastest version for this record
            $r = xModel::load('version_relation', array(
                'model_name' => 'personne',
                'id_field_value' => $personne_id,
                'xorder_by' => 'version_id',
                'xorder' => 'DESC',
                'xlimit' => 1,
                'xjoin' => array()
            ))->get(0);
            $version_id = @$r['version_id'];
            if (!$version_id) throw new xException('Error retrieving record latest version id');
            // Assigns lastest version for this record
            $this->params['version_id'] = $version_id;
        }
        // Actual (standard) record data POST
        return parent::post();
    }

    /**
     * Returns versioned 'personne' data instead of using standard 'personne' join
     * and an ad-hoc 'uptodate' field set to true if the versionned person is uptodate, false otherwise.
     * @see https://github.com/unil/iafbm/issues/118
     */
    function get($rownum=null) {
        // No need to version record if join is not required
        if (!in_array('personne', $this->join)) return parent::get($rownum);
        // Retrieves records (at current version)
        $records = parent::get($rownum);
        // Ensuring it is an array of records (in case where $rownum is defined)
        $records = isset($rownum) ? array($records) : $records;
        // Updates the 'personne' join, applying versioned personne record
        $personne_updated = false;
        foreach ($records as &$record) {
            if (!$record) continue;
            $personne = xModel::load('personne', array(
                'id' => $record['personne_id'],
                'xversion' => $record['version_id']
            ))->get(0);
            foreach ($personne as $field => $value) {
                $record["personne_{$field}"] = $value;
                $personne_updated = true;
            }
        }
        // Adds 'uptodate' ghost field
        foreach ($records as &$record) {
            // Counts versions created since the record stored version id
            $versions = xModel::load('version_relation', array(
                'model_name' => 'personne',
                'id_field_value' => $record['personne_id'],
                'version_id' => $record['version_id'],
                'version_id_comparator' => '>',
                'xjoin' => array()
            ))->get();
            // If versioned record is requested,
            // ignores versions above the requested xversion.
            $xversion = @$this->params['xversion'];
            if ($xversion) {
                foreach ($versions as $k => $version) {
                    if ($version['version_id'] >= $xversion) {
                        unset($versions[$k]);
                    }
                }
            }
            // Sets 'uptodate' ghost field
            $record['_uptodate'] = !(bool)count($versions);
        }
        // Sorts results on personne field
        // (if defined by xorder_by and xorder && personne data was updated due to versioning)
        $personne_fields = array_keys($this->foreign_mapping('personne'));
        if ($personne_updated && in_array(@$this->params['xorder_by'], $personne_fields)) {
            $success = usort($records, array($this, '_sort_results'));
            if (!$success) throw new xException('Error sorting restults');
        }
        // Returns result(s)
        return isset($rownum) ? $records[$rownum] : $records;
    }
    protected function _sort_results($a, $b) {
        $property = @$this->params['xorder_by'];
        $direction = @$this->params['xorder'];
        if (!$property) return 0;
        $return = $a[$property] > $b[$property] ? 1 : -1;
        if (strtolower($direction) == 'desc') $return = $return * -1;
        return $return;
    }
}