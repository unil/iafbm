<?php

// Creates conditions SQL strings (ignoring 'actif' field)
$conditions = array();
foreach ($d['where'] as $where) {
    if ($where['field'] == 'actif') continue;
    $conditions[] = "{$where['table']}.{$where['field']} LIKE '{$where['value']}'";
}
$conditions = implode("\n\tOR ", $conditions);

// Issues WHERE statement (including 'actif' field if applicable)
if (array_key_exists('actif', $d['model']->mapping)) {
    echo "WHERE {$d['model']->table}.actif = '1' AND (\n\t{$conditions}\n)";
} else {
    echo "WHERE {$conditions}";
}

?>