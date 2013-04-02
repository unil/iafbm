<h1>Liste des ressources versionnées</h1>

<ul>
<?php foreach ($d['controllers'] as $controller): ?>
    <li>
        <a href="<?php echo u("versions/do/history/{$controller}") ?>">
            <?php echo $controller ?>
        </a>
    </li>
<?php endforeach ?>
</ul>