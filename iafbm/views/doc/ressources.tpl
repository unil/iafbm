<h1>Ressources</h1>

<ul>
<?php foreach (xController::scan() as $controller): ?>
    <li>
        <?php
            $model = xController::load($controller)->model;
            if (!$model) continue;
            $description = xModel::load($model)->description;
        ?>
        <a href="<?php echo u("doc/do/ressources/{$controller}") ?>"><?php echo "<b>{$controller}</b>" ?></a><?php if ($description) echo ": {$description}" ?>

    </li>
<?php endforeach ?>
</ul>
