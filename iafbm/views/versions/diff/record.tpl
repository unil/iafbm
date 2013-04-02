<style>
    table th {
        font-weight: bold;
        text-align: right;
    }
    table td {
        padding: 0 5px;
    }
    .modified {
        color: #a00;
        font-weight: bold;
    }
</style>

<h1>
    Détail pour la ressource
    <a href="<?php echo u("versions/do/history/{$d['resource']}") ?>">
        <?php echo $d['resource'] ?>
    </a>
    <a href="<?php echo u("versions/do/history/{$d['resource']}/{$d['id']}") ?>">
        <?php echo $d['id'] ?>,
    </a>
    version <?php echo $d['version']['id'] ?>
</h1>

<h2>Toutes les versions</h2>
<?php foreach ($d['versions'] as $version): ?>
<?php if ($version['id'] == $d['version']['id']): ?>
    <?php echo $version['id'] ?>
<?php else: ?>
    <a href="<?php echo u("versions/do/history/{$d['resource']}/{$d['id']}/{$version['id']}") ?>">
        <?php echo $version['id'] ?>
    </a>
<?php endif ?>
<?php endforeach ?>

<h2>Informations sur la version</h2>
<p>
    Version <?php echo $d['version']['id'] ?>
    du <?php echo xUtil::datetime($d['version']['created']) ?>
    par <?php echo $d['version']['creator'] ?>
</p>
<p>
    Type de version: <?php echo $d['version']['operation'] ?>
<?php if ($d['version']['commentaire']): ?>
    (<?php echo $d['version']['commentaire'] ?>)
<?php endif ?>
</p>

<h2>Champs modifiés</h2>
<table>
<?php foreach ($d['diff'] as $diff): ?>
    <tr>
        <th>
            <?php echo $diff['field_name'] ?>:
        </th>
        <td>
            <?php echo $diff['old_value'] ? $diff['old_value'] : '<i>(nil)</i>' ?>
        </td>
        <td>
            ..
        </td>
        <td>
            <?php echo $diff['new_value'] ? $diff['new_value'] : '<i>(nil)</i>' ?>
        </td>
    </tr>
<?php endforeach ?>
</table>

<h2>Enregistrement complet</h2>
<?php
    $modified_fields = array_map(function($diff) {
        return $diff['field_name'];
    }, $d['diff']);
?>
<table>
<?php if( $d['record']): foreach ($d['record'] as $field => $value): ?>
    <tr>
        <th>
            <?php echo in_array($field, $modified_fields) ? "<span class=\"modified\">{$field}</span>" : $field ?>:
        </th>
        <td>
            <?php echo $value ?>
        </td>
    </tr>
<?php endforeach ?>
<?php else: ?>
<i>(nil)</i>
<?php endif ?>
</table>