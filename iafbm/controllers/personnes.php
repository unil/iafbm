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

    var $export_fields_labels = array(
        'id' => 'id',
        'id_unil' => 'id UNIL',
        'id_chuv' => 'id CHUV',
        'id_adifac' => 'id ADIFAC',
        'nom' => 'Nom',
        'prenom' => 'Prénom',
        'date_naissance' => 'Date de naissance',
        'no_avs' => 'N° AVS',
        'personne_type_nom' => 'Type',
        'genre_nom' => 'Genre',
        'personne_denomination_nom' => 'Dénomination',
        'personne_denomination_abreviation' => 'Dénomination (abrév)',
        'etatcivil_nom' => 'Etat civil',
        'pays_nom' => 'Pays d\'origine',
        'canton_nom' => 'Canton d\'origine',
        'permis_nom' => 'Permis',
        'adresse_adresse_type_nom' => 'Type d\'adresse',
        'adresse_rue' => 'Rue',
        'adresse_npa' => 'NPA',
        'adresse_lieu' => 'Lieu',
        'adresse_pays_nom' => 'Pays',
        'personne_telephone_adresse_type_nom' => 'Type de téléphone',
        'personne_telephone_countrycode' => 'Tél (indicatif)',
        'personne_telephone_telephone' => 'Tél',
        'personne_email_adresse_type_nom' => 'Type d\'email',
        'personne_email_email' => 'Email',
        'formation_abreviation' => 'Formation',
        'activite_section_nom' => 'Section',
        'activite_section_code' => 'Section (abrév)',
        'activite_activite_nom_nom' => 'Activité',
        'activite_activite_nom_abreviation' => 'Activité (abrév)'
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
        // Config
        $export_dir = '/tmp';
        $modes = array(
            'Windows' => array(
                'xseparator' => ',',
                'xnewline' => "\n",
                'xencoding' => 'ISO-8859-1'
            ),
            'Mac' => array(
                'xseparator' => ';',
                'xnewline' => "\r",
                'xencoding' => 'ISO-8859-1'
            )
        );
        // Cleans exported files older than x (avoid cron setup)
        $ttl = 1*24*60*60; // 1 day
        foreach (glob("{$export_dir}/export-personnes-*.csv") as $file) {
            if (mktime()-fileatime($file) > $ttl) unlink($file);
        }
        // Actual action behaviour
        if (@$this->params['fields']) {
            // Generates CSV file
            $modeparams = $modes[@$this->params['mode']];
            if (!$modeparams) $modeparams = $modes[0];
            $csvparams = array_merge(array(
                'fields' => $this->params['fields'],
                'xformat' => 'csv'
            ), $modeparams);
            $csv = xFront::load('api', $csvparams)->encode($this->export());
            $file = 'export-personnes-'.md5($csv).'.csv';
            file_put_contents("{$export_dir}/{$file}", $csv);
            header('Location: '.xUtil::url("personnes/do/export?file={$file}"));
            exit;
        } elseif (@$this->params['file']) {
            // Displays download link
            $data['link'] = xUtil::url("personnes/do/export?dl={$this->params['file']}");
            return xView::load('personnes/export-download', $data, $this->meta);
        } elseif (@$this->params['dl']) {
            // Actual file download
            $file = $this->params['dl'];
            if (!preg_match('/^export-personnes-\w{32}\.csv$/', $file)) {
                throw new xException('Unauthorized file pattern', 403);
            }
            $filename = 'export-personnes.csv';
            header('Content-Type: application/csv');
            header("Content-Disposition: attachment; filename={$filename}");
            $file = "{$export_dir}/{$this->params['dl']}";
            print file_exists($file) ? file_get_contents($file) : null;
            exit;
        } else {
            // Export configuration page
            $data['modes'] = $modes;
            return xView::load('personnes/export', $data, $this->meta);
        }
    }

    function export() {
        // Models joins to traverse (1..1 or n..1 joins)
        $models_joins = array(
            //'model-name|join-name, join-name-2' => 'foreign-table-field-name',
            'personne_type' => 'personne_type_id',
            'genre' => 'genre_id', // Must exist before 'personne_denomination'
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
            '       AND personnes_adresses.defaut = 1',
            '    LEFT JOIN personnes_telephones',
            '       ON  personnes_telephones.personne_id = personnes.id AND personnes_telephones.actif = 1',
            '       AND personnes_telephones.actif = 1',
            '       AND personnes_telephones.defaut = 1',
            '    LEFT JOIN personnes_emails',
            '       ON  personnes_emails.personne_id = personnes.id',
            '       AND personnes_emails.actif = 1',
            '       AND personnes_emails.defaut = 1',
            '    LEFT JOIN personnes_formations',
            '       ON  personnes_formations.personne_id = personnes.id',
            '       AND personnes_formations.actif = 1',
            '    LEFT JOIN personnes_activites',
            '       ON  personnes_activites.personne_id = personnes.id',
            '       AND personnes_activites.actif = 1'
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
                // Manages epicene 'personne_denomination_nom' (female/male):
                // replaces 'denomination.nom' with female/male field
                // FIXME: this should be placed in personne_denomination model or controller
                if ($model == 'personne_denomination' && @$row['personne_denomination_id']) {
                    if ($row['genre_initiale']=='F') {
                        $foreign_row['nom'] = "{$row['genre_intitule']} la {$foreign_row['nom_feminin']}";
                    } elseif ($row['genre_initiale']=='H') {
                        $foreign_row['nom'] = "{$row['genre_intitule']} le {$foreign_row['nom_masculin']}";
                    }
                }
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
                    $row["{$model}_{$field}"] = $value;
                }
            }
        }
        // Substitutes fields names with labels
        // removing unwanted fields if required by 'fields' parameter
        $fields_labels = $this->export_fields_labels;
        if (@$this->params['fields'])  $fields_labels = xUtil::filter_keys(
            $fields_labels,
            xUtil::arrize($this->params['fields'])
        );
        $rows = array_map(function($row) use ($fields_labels) {
            $fields = array_keys($fields_labels);
            $labelled_row = array();
            foreach ($row as $field => $value) {
                if (!in_array($field, $fields) || !$fields_labels[$field]) continue;
                $labelled_row[$fields_labels[$field]] = $value;
            }
            return $labelled_row;
        }, $rows);
        // TODO: order fields according $fields_labels order
        // Returns export
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