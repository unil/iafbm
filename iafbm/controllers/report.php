<?php

class ReportController extends iaExtRestController {

    function indexAction() {
        return xView::load('report/index', array(), $this->meta);
    }

    function effectifCorpsEnseignantAction() {
        // If all regexps are present in array,
        // returns true and deletes matching cells,
        // else, returns false and does nothing
        $array_preg = function($regexps, &$array) {
            $array_copy = $array;
            $matches = array();
            $regexps = xUtil::arrize($regexps);
            foreach ($regexps as $i => $regexp) {
                $m = preg_grep($regexp, $array_copy);
                if ($m===false) throw new xException("Error with regexp: {$regexp}");
                $matches = array_merge($matches, $m);
                // Deletes matched regexp
                if ($m) unset($regexps[$i]);
                // Deletes matched cells
                foreach ($m as $k => $v) unset($array_copy[$k]);
            }
            // Regexps array not empty means that some didn't match
            if ($regexps) return false;
            // Modifies original array
            $array = $array_copy;
            // returns true
            return true;
        };
        // Defines output table contents, using structure
        // header => array(items)
        $grouping = array(
            "Corps professoral" => array(
                'PO', 'PAS', 'PAST'
            ),
            "Corps intermédiaire" => array(
                'PD/MER1', 'PD/MER2', 'MER1', 'MER2', 'MA'
            ),
            "Participants à l'enseignement" => array(
                'PD', 'PTIT', 'PI', 'CC'
            )
        );
        // Defines activites-labels => regexp-expression.
        // Note: order matters because matching cells will be deleted
        $items = array(
            'PD/MER1' => array('/^PD$/', '/^MER1/'),
            'PD/MER2' => array('/^PD$/', '/^MER2/'),
            'PO' => '/^PO/',
            'PAST' => '/^PAST/',
            'PAS' => '/^PAS/',
            'MER1' => array('/^MER1/'),
            'MER2' => array('/^MER2/'),
            'MA' => '/^MA/',
            'PD' => array('/^PD/'),
            'PTIT' => '/^PTIT/',
            'PI' => '/^PI/',
            'CC' => '/^CC/',
        );
        // Counts activites personne-by-personne
        $personnes = xModel::load('personne', array(
            'actif' => 1,
            'personne_type_id' => 4,
            'personne_type_id_comparator' => '!=',
            'xreturn' => 'id',
            'xjoin' => ''
        ))->get();
        $personnes_ids = array_map(function($item) { return $item['id']; }, $personnes);
        //
        $counts = array();
        foreach ($personnes_ids as $id) {
            foreach (array('SSF', 'SSC') as $section) {
                // Fetches personne activites
                $result = xModel::load('personne_activite', array(
                    'personne_id' => $id,
                    'section_code' => $section,
                    'en_vigueur' => true,
                    'xreturn' => 'section_code,activite_nom_abreviation'
                ))->get();
                // Creates a flat activites array
                $activites = array();
                foreach ($result as $activite) $activites[] = $activite['activite_nom_abreviation'];
                $activites = array_unique($activites);
                // Counts activites
                foreach ($items as $item => $regexps) {
                    if ($array_preg($regexps, $activites)) {
                        @$counts[$section][$item]++;
                    }
                }
                // TODO: Cellule "Autres" dans le(s) tableaux de report?
                //if ($activites) throw new xException('Some activites were not counted', 500, $activites);
            }
        }
        // Programatically creates per activite sum (SSC+SSF)
        foreach ($counts as $section => $activites) {
            foreach ($activites as $activite => $count) {
                $counts['FBM'][$activite] = @$counts['FBM'][$activite] + $count;
            }
        }
        //
        $data = array(
            'grouping' => $grouping,
            'counts' => $counts
        );
        $this->params['xorientation'] = 'landscape';
        $this->_print(xView::load('report/effectif-corps-enseignant', $data, $this->meta));
    }

    function _print($html) {
        return xController::load('print', $this->params)->_print($html);
    }
}