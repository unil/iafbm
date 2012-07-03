<?php

class PersonnesController extends iaExtRestController {

    var $model = 'personne';

    var $query_fields = array(
        'nom', 'prenom', 'pays_nom', 'pays_code', 'date_naissance'
    );
    var $query_fields_transform = array(
        'date_naissance' => 'date,date-binomial'
    );
    var $query_join = 'pays';

    var $sort_fields_substitutions = array(
        'pays_id' => array(
            'field' => 'pays_nom',
            'join' => 'pays'
        )
    );

    function indexAction() {
        $data = array(
            'title' => 'Personnes',
            'id' => 'personnes',
            'model' => 'Personne'
        );
        return xView::load('common/extjs/grid', $data, $this->meta);
    }

    function detailAction() {
        $data = array(
            'id' => $this->params['id'],
        );
        return xView::load('personnes/detail', $data, $this->meta);
    }

    function exportAction() {
        return xView::load('personnes/export', $data, $this->meta);
    }

    function export() {
        // Fields labels
        // TODO: Move this into class property or method
        //       for use by both export() and exportAction()
        $fields_labels = array(
            // ...
            'pays_nom' => 'Pays d\'origine'
            // ...
        );
        // Models joins to traverse (1..1 or n..1 joins)
        $models_joins = array(
            //'model-name|join-name, join-name-2' => 'foreign-table-field-name',
            'personne_type' => 'personne_type_id',
            'genre' => 'genre_id',
            'personne_denomination' => 'personne_denomination_id',
            'etatcivil' => 'etatcivil_id',
            'pays' => 'pays_id',
            'canton' => 'canton_id',
            'permis' => 'permis_id',
            'adresse|pays,adresse_type' => 'adresse_id',
            'personne_telephone|adresse_type' => 'telephone_id',
            'personne_email|adresse_type' => 'email_id',
            'formation' => 'formation_id',
            'activite|activite_nom,activite_type,section' => 'activite_id',
        );
        // Initializes SQL joins list with 1..n joins
        $q = implode("\n", array(
            'SELECT',
            '    personnes.*,',
            '    personnes_adresses.adresse_id AS adresse_id,',
            '    personnes_telephones.id AS telephone_id,',
            '    personnes_emails.id AS email_id,',
            '    personnes_formations.id AS formation_id,',
            '    personnes_activites.id as activite_id',
            'FROM personnes',
            '    LEFT JOIN personnes_adresses',
            '       ON  personnes_adresses.personne_id = personnes.id',
            '       AND personnes_adresses.actif = 1',
            //'       AND personnes_adresses.defaut = 1',
            '    LEFT JOIN personnes_telephones',
            '       ON  personnes_telephones.personne_id = personnes.id AND personnes_telephones.actif = 1',
            '       AND personnes_telephones.actif = 1',
            //'       AND personnes_telephones.defaut = 1',
            '    LEFT JOIN personnes_emails',
            '       ON  personnes_emails.personne_id = personnes.id',
            '       AND personnes_emails.actif = 1',
            //'       AND personnes_emails.defaut = 1',
            '    LEFT JOIN personnes_formations',
            '       ON  personnes_formations.personne_id = personnes.id',
            '       AND personnes_formations.actif = 1',
            '    LEFT JOIN personnes_activites',
            '       ON  personnes_activites.personne_id = personnes.id',
            '       AND personnes_activites.actif = 1',
            'LIMIT 10', // TODO: Dev purpose, remove this
        ));
        // Creates 'personne' result array
        $r = xModel::q($q);
        while ($row = mysql_fetch_assoc($r)) $rows[] = $row;
        // Creates n..1 joins fields
        foreach ($models_joins as $join_info => $tablefield) {
            @list($model, $join) = explode('|', $join_info);
            foreach ($rows as &$row) {
                // Fetches the foreign row related to the given join
                // (there can be only one row, otherwise there is a problem)
                $foreign_model = xModel::load($model, array(
                    'id' => $row[$tablefield],
                    'xjoin' => $join
                ));
                $foreign_row = $foreign_model->get();
                if (count($foreign_row) > 1) throw new xException(
                    'Model joins should return no more than 1 result',
                    500,
                    array(
                        'model' => $model,
                        'params' => $foreign_model->params,
                        'results' => $foreign_row
                    )
                );
                $foreign_row = array_shift($foreign_row);
                // If no foreign_row (eg. empty foreign id)
                // simulates an empty row for data-structure consistency
                if (!$foreign_row) {
                    $mapping = array_merge(
                        $foreign_model->mapping,
                        $foreign_model->foreign_mapping()
                    );
                    $foreign_row = array_combine(
                        array_keys($mapping),
                        array_fill(0, count($mapping), null)
                    );
                }
                // Prefixes foreign rows with model name
                // and merges with the 'personne' $row
                foreach ($foreign_row as $field => $value) {
/* TODO: review this fieldnames cleaning logic
                    // Prevents repeating foreign model name (eg: activite_activite_nom)
                    $field = (substr($field, 0, strlen($model)) == $model) ?
                        $field : "{$model}_{$field}";
                    // Prevents repeating source model name (eg: personne_email)
                    $field = (substr($field, 0, strlen($this->model)) == $this->model) ?
                        substr($field, strlen($this->model)+1) : $field;
                    // Merges foreign row field with 'personne' $row
                    $row[$field] = $value;
*/
                    // TODO: use raw $field but substitute
                    //       with $fields_labels array
                    $row["{$model}_{$field}"] = $value;
                }
            }
        }
        // Filters unwanted fields
        $remove_fields_regexp = array(
            '/^actif$/',
            '/.*_actif$/',
            '/.*_id$/',
            '/.*_id_.*/',
            '/.*_defaut$/'
        );
        $rows = array_map(function($row) use ($remove_fields_regexp) {
            foreach ($row as $field => $value) {
                foreach ($remove_fields_regexp as $regexp) {
                    if (preg_match($regexp, $field)) {
                        unset($row[$field]);
                    }
                }
            }
            return $row;
        }, $rows);
        return $rows;
    }

    function get() {
        $personnes = parent::get();
        // Adds '_activites' ghost field (if applicable)
        $return = xModel::load($this->model, $this->params)->return;
        if (xUtil::in_array(array('*', '_activites'), $return)) {
            foreach ($personnes['items'] as &$personne) {
                // Fetches 'Fonction' for the current 'Personne'
                $fonctions = xModel::load('personne_activite', array(
                    'personne_id' => $personne['id'],
                    'xjoin' => 'activite,activite_nom',
                    'xorder_by' => 'activite_nom_abreviation',
                    'xorder' => 'ASC'
                ))->get();
                // Creates a CSV list of 'Fonction'
                $f = array();
                foreach($fonctions as $fonction) {
                    $f[] = $fonction['activite_nom_abreviation'];
                }
                // Adds it to the resultset
                $personne['_activites'] = implode(', ', $f);
            }
        }
        return $personnes;
    }

    /**
     * Ensures 'nom' + 'prenom' fields begin with capitals.
     * @see transform_params()
     */
    function post() {
        $this->transform_params();
        return parent::post();
    }

    /**
     * Ensures 'nom' + 'prenom' fields begin with capitals.
     * @see transform_params()
     */
    function put() {
        $this->transform_params();
        return parent::put();
    }

    protected function transform_params() {
        foreach (array('nom', 'prenom') as $p) {
            $param = &$this->params['items'][$p];
            if (isset($param))
                $param = $this->ucnames($param);
        }
    }
    protected function ucnames($str) {
        return str_replace('- ','-',ucwords(str_replace('-','- ',$str)));
    }
}