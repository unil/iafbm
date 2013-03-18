<?php
// Transforms members structure (this feels dirty, sorry)
function concat($m) {
    $membres = array();
    $fields_to_keep = array('id', 'personne_id', 'personne_denomination_abreviation', 'nom_prenom', 'personne_nom', 'personne_prenom', 'commission_fonction_id', 'commission_fonction_nom', 'fonction_complement', 'version_id');
    $fields_to_concat = array('personne_denomination_abreviation', 'commission_fonction_id', 'commission_fonction_nom', 'fonction_complement');
    foreach ($m as $membre) {
        $membre = xUtil::filter_keys($membre, $fields_to_keep);
        // Selects member unique id (personne_id for members, id for non-members)
        $id = @$membre['personne_id'] ? $membre['personne_id'] : $membre['id'];
        if (@!$membres[$id]) {
            $membres[$id] = $membre;
            foreach($fields_to_concat as $field) {
                $membres[$id][$field] = array($membre[$field]);
            }
        } else {
            foreach($fields_to_concat as $field) {
                $membres[$id][$field] = array_merge($membres[$id][$field], array($membre[$field]));
            }
        }
    }
    return $membres;
}

// Returns the CSS to be used for <table> <tr>
function cssclass($membre) {
    // Determines if personne is 'president'
    $class = in_array(1, $membre['commission_fonction_id']) ? 'president' : null;
    return $class;
}

// Returns membre '_activites' ghost field
function activite($membre) {
    // Retrieves personne._activite pseudo-field
    if ($membre['personne_id']) {
        $personne = xController::load('personnes', array(
            'id' => $membre['personne_id'],
            'xversion' => $membre['version_id']
        ))->get();
        return @$personne['items'][0]['_activites'];
    }
    return '-';
}
?>

<style>
table {
    line-height: 1.2em;
}
tr {
    vertical-align: top;
}
tr.president {
    font-weight: bold;
}
</style>

<h1>Membres de la <?php echo lcfirst($d['commission']['nom']) ?>  (avec droit de vote)</h1>
<hr/>
<table class="noborder" style="vertical-align:top">
<?php if ($d['membres']) foreach(concat($d['membres']) as $membre): ?>
  <tr class="<?php echo cssclass($membre) ?>">
    <td style="width:15%">
        <?php echo implode('<br/>', $membre['personne_denomination_abreviation']) ?>
    </td>
    <td style="width:30%">
        <?php echo $membre['nom_prenom'] ?>
    </td>
    <td style="width:35%">
        <?php $count = count($membre['commission_fonction_nom']) ?>
        <?php for($i=0; $i<$count; $i++): ?>
            <?php $fonction =  $membre['commission_fonction_nom'][$i] ?>
            <?php $complement =  $membre['fonction_complement'][$i] ?>
            <?php echo $fonction ?>
            <?php if ($complement) echo "({$complement})"  ?>
            <?php if ($i < $count-1) echo '<br/>'; ?>
        <?php endfor ?>
    </td>
    <td style="width:20%">
        <?php echo activite($membre) ?>
    </td>
  </tr>
<?php endforeach; else echo '<tr><td>Aucun membre pour cette commission</td></tr>' ?>
</table>
<hr/>
<div style="font-size:8pt <?php if (isset($_REQUEST['html'])) echo ";visibility:hidden;height:30px" ?>">
  Décanat/Unité Relève/Réf. <?php echo $d['commission']['id'] ?>
</div>

<div style="page-break-before:always"/>

<h1>Autres participants à la <?php echo lcfirst($d['commission']['nom']) ?> (sans droit de vote)</h1>
<hr/>
<table class="noborder" style="vertical-align:top">
<?php if ($d['non-membres']) foreach(concat($d['non-membres']) as $membre): ?>
  <tr class="<?php echo cssclass($membre) ?>">
    <td style="width:15%">
        <?php echo implode('<br/>', $membre['personne_denomination_abreviation']) ?>
    </td>
    <td style="width:30%">
        <?php echo $membre['nom_prenom'] ?>
    </td>
    <td style="width:35%">
        <?php $count = count($membre['commission_fonction_nom']) ?>
        <?php for($i=0; $i<$count; $i++): ?>
            <?php $fonction =  $membre['commission_fonction_nom'][$i] ?>
            <?php $complement =  $membre['fonction_complement'][$i] ?>
            <?php echo $fonction ?>
            <?php if ($complement) echo "({$complement})"  ?>
            <?php if ($i < $count-1) echo '<br/>'; ?>
        <?php endfor ?>
    </td>
    <td style="width:20%">
        <?php echo activite($membre) ?>
    </td>
  </tr>
<?php endforeach; else echo '<tr><td>Aucun autre participant pour cette commission</td></tr>' ?>
</table>
<hr/>
<div style="font-size:8pt">
  Décanat/Unité Relève/Réf. <?php echo $d['commission']['id'] ?>
</div>
