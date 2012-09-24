<?php

class ReportController extends iaExtRestController {

    function indexAction() {
        return xView::load('report/index', array(), $this->meta);
    }

    function effectifCorpsEnseignantAction() {
        // Groups definition
        $grouping = array(
            "Corps Professoral" => array(
                'PO', 'PAS', 'PAST'
            ),
            "Corps intermédiaire" => array(
                'PD/MER1', 'PD/MER2', 'MER1', 'MER2', 'MA'
            ),
            "Participants à l'enseignement" => array(
                'PD', 'PTIT', 'PI', 'CC'
            )
        );
        // Determines 'activites' ids to query
        $activites = array();
        foreach ($grouping as $items) $activites = array_merge($activites, $items);
        $activites = array_unique($activites);
        $activites_ids = array();
        //
        foreach (array('SSC','SSF') as $section) {
            foreach ($activites as $activite) {
                $r = xModel::load('activite', array(
                    'activite_nom_abreviation' => $activite,
                    'section_code' => $section
                ))->get(0);
                if (@$r['id']) $activites_ids[$section][$activite] = $r['id'];
            }
        }
        // Queries database
        $counts = array();
        foreach ($activites_ids as $section => $activites) {
            foreach ($activites as $activite => $id) {
                $count = xModel::load('personne_activite', array(
                    'activite_id' => $id,
                    'xjoin' => ''
                ))->count();
                // Stores count in data structure
                $counts[$section][$activite] = $count;
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