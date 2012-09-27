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

    function listeCorpsEnseignantAction() {
        $get_personnes_by_activites = function($activite_nom_abreviation, $section_code) {
            $data = array();
            // Determines 'activite' id
            $activites_id = array();
            $r = xModel::load('activite', array(
                'activite_nom_abreviation' => $activite_nom_abreviation,
                'section_code' => $section_code,
                'xjoin' => 'section,activite_nom'
            ))->get();
            foreach ($r as $item) $activites_id[] = $item['id'];
            $activites_id = array_unique($activites_id);
            // Fetches data
            foreach ($activites_id as $activite_id) {
                $r = xModel::load('personne_activite', array(
                    'activite_id' => $activite_id,
                    'xjoin' => ''
                ))->get();
                foreach ($r as $item) {
                    $personne_activite = $item;
                    $personne = xModel::load('personne', array('id'=>$item['personne_id']))->get(0);
                    $activite = xModel::load('activite', array('id'=>$item['activite_id']))->get(0);
                    $rattachement = xModel::load('rattachement', array('id'=>$item['rattachement_id']))->get(0);
                    $commissions = array();
                    $commissions_membres = xModel::load('commission_membre', array(
                        'personne_id'=>$item['personne_id']
                    ))->get();
                    foreach ($commissions_membres as $commission_membre) {
                        $commission_id = @$commission_membre['commission_id'];
                        $commissions[$commission_id] = xModel::load('commission', array(
                            'id' => $commission_id,
                            // FIXME: filter on commissions 'permanantes': 'commission_type_id' => ?
                        ))->get();
                    }
                    $data[] = array(
                        'personne_activite' => $personne_activite,
                        'personne' => $personne,
                        'activite' => $activite,
                        'rattachement' => $rattachement,
                        'commissions' => $commissions
                    );
                }
            }
            // Fetches and returns personne records
            return $data;
        };
        $data = $get_personnes_by_activites(array('PO', 'PO ad personam'), 'SSC');
        $this->params['xorientation'] = 'landscape';
        $this->_print(xView::load('report/liste-corps-enseignant', $data, $this->meta));
    }

    function _print($html) {
        return xController::load('print', $this->params)->_print($html);
    }
}