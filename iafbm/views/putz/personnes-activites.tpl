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
</style>

<h1>Liste des activités échues toujours en vigueur</h1>

<table>
    <tr>
<?php foreach ($d['fields'] as $header): if ($header): ?>
        <th>
            <?php echo $header ?>
        </th>
<?php endif; endforeach ?>
    </tr>
<?php foreach ($d['activites'] as $activite): ?>
    <tr>
<?php foreach ($activite as $field => $value): if ($d['fields'][$field]): ?>
        <td>
            <?php echo $value ?>
        </td>
<?php endif; endforeach ?>
        <td>
            <a href="<?php echo u("personnes/{$activite['personne_id']}") ?>">
                <img src="<?php echo u('a/img/ext/page_white_magnify.png') ?>" title="Voir">
            </a>
        </td>
    </tr>
<?php endforeach ?>
</table>