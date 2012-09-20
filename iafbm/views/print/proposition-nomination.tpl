<?php

/**
 * Creates an HTML table row according the given $innerHTML.
 * If $label or $value is empty (eg. zero length),
 * the null value is returned instead of the HTML code.
 * @param string The label for the row.
 * @param string The inner HTML for the row.
 * @param string The suffix to append to $value (if $value is not empty).
 * @return string|null The HTML table row, or null if $label or $value is empty.
 */
function row($label, $value, $value_suffix=null) {
    // Returns null if $label or $value is empty
    if (!strlen($label) || !strlen($value)) return null;
    else {
        $label_suffix = (!$label || $label=='&nbsp;') ? null : ':';
        return "<tr><th>{$label}{$label_suffix}</th><td>{$value}{$value_suffix}</td></tr>";
    }
}
?>


<style>
  h3 { margin-bottom: 0 }
  table th { width: 200px }
</style>

<table class="noborder">
  <tr><td colspan="2">
    <h3>Proposition de nomination</h3>
  </td></tr>
  <?php echo row('Faculté', 'Faculté de biologie et médecine') ?>
  <?php echo row('Section', $d['commission']['section_code']) ?>
  <?php echo row('Institut', $d['commission']['institut']) ?>
  <?php echo row('Objet', $d['proposition']['objet']) ?>
  <?php echo row('&nbsp;', '&nbsp;') ?>

  <?php echo row('Titre proposé', implode(' / ', array_filter(array(
        $d['activite']['section_code'],
        $d['activite']['activite_type_nom'],
        $d['activite']['activite_nom_abreviation']
    ))));
  ?>
  <?php echo row('Début de contrat', $d['proposition']['contrat_debut_au_plus_tot'] ? 'Au plus tôt' : xUtil::date($d['proposition']['contrat_debut'])) ?>
  <?php echo row('Fin de contrat', xUtil::date($d['proposition']['contrat_fin'])) ?>
  <?php echo row(
        "Taux d'activité/Charge horaire", $d['proposition']['charge_horaire'] ?
            implode(' ', array(
                $d['proposition']['charge_horaire'], $d['proposition']['grandeur_unite_symbole']
            )) : null);
  ?>
  <?php echo row('Indemnité', $d['proposition']['indemnite'], ' CHF') ?>
  <?php echo row('Primo loco', $d['candidat']['_primo_loco'] ? 'Oui' : 'Non') ?>
  <?php
    echo row('Autres candidats', $d['candidat']['_primo_loco'] ?
        implode('<br/>', array_map(function($candidat) {
            return "{$candidat['prenom']} {$candidat['nom']}";
        }, $d['autres-candidats'])) : null
    )
  ?>

  <?php echo row('&nbsp;', '&nbsp;') ?>
  <tr><td colspan="2">
    <h3>Coordonnées</h3>
  </td></tr>
  <?php echo row('Titre', implode(' / ', array_filter(array(
        $d['proposition']['personne_denomination_nom_masculin'],
        $d['proposition']['personne_denomination_nom_feminin']
    ))))
  ?>
  <?php echo row('Nom', @$d['candidat']['nom']) ?>
  <?php echo row('Prénom', @$d['candidat']['prenom']) ?>
  <?php echo row('Adresse', implode('<br/>', array_filter(array(
        @$d['candidat']['_adresse_defaut'],
        implode(' ', array_filter(array(
            @$d['candidat']['_npa_defaut'],
            @$d['candidat']['_lieu_defaut']
        ))),
        array_shift(xUtil::filter_keys(
            xModel::load('pays', array('id'=>@$d['candidat']['_pays_defaut_id']))->get(0),
            'nom'
        ))
    ))))
  ?>
  <?php echo row('Email', @$d['candidat']['_email_defaut']) ?>
  <?php echo row('Etat civil', @$d['candidat']['etatcivil_nom']) ?>
  <?php echo row('Date de naissance', xUtil::date(@$d['candidat']['date_naissance'])) ?>
  <?php echo row("Pays d'origine", @$d['candidat']['pays_nom']) ?>
  <?php echo row("Canton d'origine pour les Suisses", @$d['candidat']['canton_nom']) ?>
  <?php echo row('Permis', @$d['candidat']['permis_nom']) ?>
  <?php echo row('&nbsp;', '&nbsp;') ?>

<?php if (@max(array($d['candidat']['position_actuelle_fonction'], $d['candiat-formations']))): ?>
  <?php echo row('Fonction actuelle', @$d['candidat']['position_actuelle_fonction']) ?>
  <?php
    echo row('Grade universitaire', implode('<br/>', array_map(function($formation) {
        return implode(', ', array_filter(array(
            "<b>{$formation['formation_abreviation']}</b>",
            xUtil::date($formation['date_these']),
            $formation['lieu_these']
        )));
    }, $d['candidat-formations'])))
  ?>
  <?php echo row('&nbsp;', '&nbsp;') ?>
<?php endif ?>

  <?php
    $preavis_labels = array(
        'decanat_date' => '(Validation par le Décanat)',
        'cf_date' => '(Validation par le CF)'
    );
    $date = xUtil::date($d['proposition']["commission_validation_{$d['proposition']['date_preavis_champs']}"]);
    echo row(
        'Date préavis',
        $date ? "{$date} {$preavis_labels[$d['proposition']['date_preavis_champs']]}" : null
    )
  ?>
  <?php echo row('Observations', nl2br($d['proposition']['observations'])) ?>
  <?php echo row('Date', xUtil::date(mktime())) ?>

<?php if (@max(xUtil::filter_keys($d['proposition'], array('annexe_rapport_commission', 'annexe_cahier_des_charges', 'annexe_cv_publications', 'annexe_declaration_sante')))): ?>
  <?php echo row('&nbsp;', '&nbsp;') ?>
  <tr><td colspan="2">
    <h3>Annexes</h3>
  </td></tr>
  <?php echo row('Rapport commission', $d['proposition']['annexe_rapport_commission'] ? 'X' : null) ?>
  <?php echo row('Cahier des charges', $d['proposition']['annexe_cahier_des_charges'] ? 'X' : null) ?>
  <?php echo row('CV et liste publications', $d['proposition']['annexe_cv_publications'] ? 'X' : null) ?>
  <?php echo row('Déclaration de santé', $d['proposition']['annexe_declaration_sante'] ? 'X' : null) ?>
<?php endif ?>

<?php if (@max(xUtil::filter_keys($d['proposition'], array('imputation_fonds', 'imputation_centre_financier', 'imputation_unite_structurelle', 'imputation_numero_projet')))): ?>
  <?php echo row('&nbsp;', '&nbsp;') ?>
  <tr><td colspan="2">
    <h3>Imputation</h3>
  </td></tr>
  <?php echo row('Fond', $d['proposition']['imputation_fonds']) ?>
  <?php echo row('Centre financier', $d['proposition']['imputation_centre_financier']) ?>
  <?php echo row('Unité structurelle', $d['proposition']['imputation_unite_structurelle']) ?>
  <?php echo row('Numéro de projet', $d['proposition']['imputation_numero_projet']) ?>
<?php endif ?>
</table>
<?php


?>