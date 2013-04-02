<h1>
    Versions pour la
    <a href="<?php echo u('versions/do/history') ?>">resource</a>
    <?php echo $d['resource'] ?>
</h1>

<ul>
<?php foreach ($d['versions'] as $version): ?>
    <li>
        <a href="<?php echo u("versions/do/history/{$d['resource']}/{$version['id_field_value']}") ?>">
            <?php echo $version['id_field_value'] ?>
        </a>
        (<?php echo $version['count'] ?> versions)
    </li>
<?php endforeach ?>
</ul>