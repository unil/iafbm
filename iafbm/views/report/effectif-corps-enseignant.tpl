<style>
    table {
        width: 100%;
    }
    table td,
    table th {
        border: 1px solid black;
        padding: 5px 10px;
        text-align: center;
    }
    table th {
        font-weight: bold;
    }

    th.title { background-color: #ddd }
    td.hidden,
    th.hidden { border: none }

    th.global { color: #060 }
    th.corps0 { color: #a0a }
    th.corps1 { color: #00a }
    th.corps2 { color: #a00 }
</style>

<h1>Résumé corps enseignant FBM/UNIL</h1>

<?php echo table($d, 'SSC') ?>
<br/>
<?php echo table($d, 'SSF') ?>
<br/>
<?php echo table($d, 'FBM') ?>


<?php function table($d, $section) { ?>
<table>
    <tr>
        <th class="hidden">&nbsp;</th>
        <?php $colspan = array_reduce($d['grouping'], function($whole,$item){return $whole+count($item);}) ?>
        <th class="title global" colspan="<?php echo $colspan ?>">
            <?php $section_title = $section=='FBM' ? 'SSC+SSF' : $section ?>
            UNIL - FBM - <?php echo $section_title ?>
        </th>
    </tr>
    <tr>
        <th class="hidden">&nbsp;</th>
<?php foreach ($d['grouping'] as $corps => $activites): ?>
        <th colspan="<?php echo count($activites) ?>" class="<?php $i=(!isset($i))?0:++$i; echo "corps{$i}" ?>">
            <?php echo $corps ?>
        </td>
<?php endforeach ?>
    </tr>
    <tr>
        <th class="hidden">&nbsp;</th>
<?php foreach ($d['grouping'] as $corps => $activites): ?>
<?php     foreach ($activites as $activite): ?>
        <td>
            <?php echo $activite ?>
        </td>
<?php     endforeach ?>
<?php endforeach ?>
    </tr>
    <tr>
        <th><?php echo $section ?></th>
<?php foreach ($d['grouping'] as $corps => $activites): ?>
<?php     foreach ($activites as $activite): ?>
        <td>
            <?php echo (int)$d['counts'][$section][$activite] ?>
        </td>
<?php     endforeach ?>
<?php endforeach ?>
    </tr>
    <tr>
        <th rowspan="2">Totaux</th>
<?php unset($i) ?>
<?php foreach ($d['grouping'] as $corps => $activites): ?>
<?php
        $total = 0;
        foreach ($activites as $activite) $total += $d['counts'][$section][$activite];
?>
        <th colspan="<?php echo count($activites) ?>" class="<?php $i=(!isset($i))?0:++$i; echo "corps{$i}" ?>">
            <?php echo $total ?>
        </td>
<?php endforeach ?>
    </tr>
    <tr>
<?php
        $grandtotal = 0;
        foreach ($d['grouping'] as $activites) foreach ($activites as $activite) $grandtotal += $d['counts'][$section][$activite];
?>
        <?php $colspan = array_reduce($d['grouping'], function($whole,$item){return $whole+count($item);}) ?>
        <th class="global" colspan="<?php echo $colspan ?>">
            <?php echo $grandtotal ?>
        </th>
    </tr>
</table>
<?php } ?>

<p style="text-align:right">
Date: <?php echo date('d.m.Y') ?>
</p>