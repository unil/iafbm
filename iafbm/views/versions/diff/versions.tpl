<h1>
    Historique pour la ressource
    <a href="<?php echo u("versions/do/history/{$d['resource']}") ?>">
        <?php echo $d['resource'] ?>
    </a>
    <?php echo $d['id'] ?>
</h1>

<?php
    $operations = array(
        'put' => 'créé',
        'post' => 'modifié',
        'delete' => 'supprimé'
    );
?>

<ul>
<?php foreach ($d['versions'] as $version): ?>
    <li>
        <a href="<?php echo u("versions/do/history/{$version['table_name']}/{$version['id_field_value']}/{$version['id']}") ?>">
            <?php echo $version['id'] ?>
        </a>
        <?php echo $operations[$version['operation']] ?>
        le <?php echo xUtil::datetime($version['created']) ?>
        (<?php echo xUtil::timeago($version['created']) ?>)
        par <?php echo $version['creator'] ?>
    </li>
<?php endforeach ?>
</ul>