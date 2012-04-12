<?php
    $model = $d['model'];
    $where = $d['where'];
    //xUtil::pre($where);
?>
WHERE 1=1

<?php foreach($where as $i => $w): ?>
<?php if($w['field'] == 'actif' && $w['table'] == $model->maintable): ?>
<?php echo "AND\n" ?>
<?php echo "\t`{$w['table']}`.`actif` = '{$w['value']}'\n" ?>
<?php unset($where[$i]) ?>
<?php endif ?>
<?php endforeach ?>

<?php while ($where): ?>
<?php $operator = "AND" ?>
<?php echo "{$operator} (\n" ?>
<?php echo "\t1=0\n" ?>
<?php
    foreach($where as $i => &$w) {
        $w['value'] = xUtil::arrize($w['value']);
        $value = array_shift($w['value']);
        if (!$w['value']) unset($where[$i]);
        $value = "%{$value}%";
        $v = $model->escape($value);
        echo "\tOR `{$w['table']}`.`{$w['field']}` LIKE {$v}\n";
    }
?>
)
<?php endwhile ?>