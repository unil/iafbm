<?php
    $controllername = $d['controller'];
    $modelname = xController::load($d['controller'])->model;
    $model = xModel::load($modelname, $d['params']);
?>

<h1>Ressource</h1>
<b><?php echo $d['controller'] ?></b><?php if (@$model->description) echo ": {$model->description}" ?>

<h1>Champs</h1>
<ul>
<?php foreach ($model->mapping as $field => $dbfield): ?>
    <li>
        <?php echo "<b>{$field}</b>" ?><?php if (@$model->labels[$field]) echo ": {$model->labels[$field]}" ?>
    </li>
<?php endforeach ?>
</ul>

<h1>Champs étrangers</h1>
<?php $foreign_mapping = $model->foreign_mapping() ?>
<ul>
<?php if ($foreign_mapping): ?>
<?php foreach ($foreign_mapping as $field => $dbfield): ?>
    <li>
        <?php echo "<b>{$field}</b>" ?>
    </li>
<?php endforeach ?>
<?php else: ?>
    <li><i>(aucun)</i></li>
<?php endif ?>
</ul>

<h1>Relations</h1>
<ul>
<?php if ($model->joins): ?>
<?php foreach ($model->joins as $join => $description): ?>
<?php
    $join_controller = null;
    foreach (xController::scan() as $controller) {
        if ($join == xController::load($controller)->model) {
            $join_controller = $controller;
            break;
        }
    }
?>
    <li>
        <a href="<?php echo u("doc/do/ressources/{$join_controller}") ?>">
            <?php echo "<b>{$join_controller}</b>" ?>
        </a>
        <?php if (in_array($join, $model->join)): ?>
        (par défaut)
        <?php endif ?>
    </li>
<?php endforeach ?>
<?php else: ?>
    <li><i>(aucune)</i></li>
<?php endif ?>
</ul>

<h1>URLs</h1>
<?php
    $interesting_id = @array_shift(array_shift(
        xModel::load($modelname, array(
            'xreturn' => 'id',
            'xlimit' => 1
        ))->get()
    ));
    $interesting_field = @array_shift(array_diff(
        $model->mapping, array('id', 'actif', 'id_unil', 'id_chuv', 'id_adifac', 'actif')
    ));
    $interesting_value = @array_shift(array_shift(
        xModel::load($modelname, array(
            'xreturn' => $interesting_field,
            'xlimit' => 1
        ))->get()
    ));
    // Creates typical web-service usage URLs
    $urls = array(
        'Toutes entités' => "api/{$controllername}",
        'Entité par id' => "api/{$controllername}/{$interesting_id}",
        'Entité par id' => "api/{$controllername}/{$interesting_id}",
        'Recherche sur un champs' => "api/{$controllername}?{$interesting_field}={$interesting_value}",
        'Recherche fulltext' => "api/{$controllername}?xquery={$interesting_value}",
    );
?>
<ul>
<?php foreach ($urls as $description => $url): ?>
    <li>
        <b><?php echo $description ?></b>:
        <a href="<?php echo u($url, true) ?>"><?php echo u($url, true) ?></a>
    </li>
<?php endforeach ?>
</ul>