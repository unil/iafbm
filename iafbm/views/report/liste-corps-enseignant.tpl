<style>
    table {
        width: 100%;
    }
    table td,
    table th {
        border: 1px solid black;
        padding: 5px 10px;
    }
    table th {
        font-weight: bold;
    }

    th.title {
        background-color: #ddd;
        text-align: center;
    }
</style>


<h1>Liste du corps enseignant - {Section des sciences cliniques}</h1>
<table>
    <tr>
        <th class="title" colspan="10">Professeurs ordinaires (<?php echo count($d) ?> dont {X} PO ad personam)</th>
    </tr>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Date de naissance</th>
        <th>Titre</th>
        <th>Service</th>
        <th>Taux Activ. Inst. état <?php echo xUtil::date(mktime()) ?></th>
        <th colspan="2">Debut de mandat</th>
        <th>Conseil de faculté {oui/non}</th>
        <th>Commissions permanantes, y c. CPA</th>
    </tr>
<?php foreach ($d as $item): ?>
    <tr>
        <td><?php echo $item['personne']['nom'] ?></td>
        <td><?php echo $item['personne']['prenom'] ?></td>
        <td><?php echo xUtil::date($item['personne']['date_naissance']) ?></td>
        <td><?php echo $item['activite']['activite_nom_abreviation'] ?></td>
        <td><?php echo $item['rattachement']['nom'] ?></td>
        <td><?php echo $item['personne_activite']['taux_activite'] ?>%</td>
        <td><?php echo xUtil::date($item['personne_activite']['debut']) ?></td>
        <td><?php echo xUtil::date($item['personne_activite']['fin']) ?></td>
        <td><?php echo $item[''][''] ?>?</td>
        <td><?php if ($item['commissions']) foreach($item['commissions'] as $commission) echo $commission['nom'].'<br/>'; else echo '-' ?></td>
    </tr>
<?php endforeach ?>
</table>