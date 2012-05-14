<style>

em {
    font-weight: bold;
}

table.user-detail {
    width: 900px;
    margin: 10px;
}
table.user-detail tr:hover {
    background-color: #ffc;
}
table.user-detail td,
table.user-detail th {
    width: 50px;
    border: 1px solid #ccc;
    padding: 5px;
}
table.user-detail th {
    vertical-align: top;
    font-weight: bold;
    background-color: #eee;
}
table.user-detail th img {
    float: right;
}

.user-detail-permission {
    width: 215px;
    float: left;
    padding: 10px;
    margin-right: 3px;
    margin-bottom: 3px;
    background-color: #f5f5f5;
}
.user-detail-permission-model {
    font-weight: bold;
}
</style>


<h1>
    <img src="<?php echo u('a/img/controllers/user/user.png') ?>" alt="Utilisateur:"/>
    &nbsp;
    <?php echo $d['username'] ?>
</h1>

<table class="user-detail">
    <tr>
        <th>
            <img src="<?php echo u('a/img/controllers/user/report_user.png') ?>"/>
            Roles
        </th>
        <td>
            <?php echo implode('<br/>', $d['roles']) ?>
        </td>
    </tr>
    <tr>
        <th>
            <img src="<?php echo u('a/img/controllers/user/tick.png') ?>"/>
            Permissions
        </th>
        <td>
<?php foreach ($d['permissions']['models'] as $model => $operations): ?>
            <div class="user-detail-permission">
                <span class="user-detail-permission-model">
                    <?php echo $model ?>
                </span>
                <br/>
                <span class="user-detail-permission-operations">
                    <?php echo $operations ? $operations : '-' ?>
                </span>
            </div>
<?php endforeach ?>
        </td>
    </tr>
    <tr>
        <th>
            <img src="<?php echo u('a/img/controllers/user/page_white_edit.png') ?>"/>
            Activité
        </th>
        <td>
<?php
    $count = $d['versions']['count'];
    $total = $d['versions']['total'];
    $first = xUtil::timestamp($d['versions']['first']['created']);
    $last = xUtil::timestamp($d['versions']['last']['created']);
    $timespan = $last-$first;
    $avg_day = $count/$timespan*60*24;
    $rate_versions = $count/$total;
    $rate_modifications = $d['modifications']['count']/$d['modifications']['total'];
?>
            <em><?php echo $count ?></em>
            versions créées en
            <em><?php echo round($timespan/60/24, 0, PHP_ROUND_HALF_DOWN) ?></em>
            jours
            <br/><br/>
            <em><?php echo round($avg_day, 0) ?></em>
            versions par jour
            <br/><br/>
            <em><?php echo round($rate_versions*100, 0) ?></em>%
            des versions
            <br/><br/>
            <em><?php echo round($rate_modifications*100, 0) ?></em>%
            des modifications
        </td>
    </tr>
</table>