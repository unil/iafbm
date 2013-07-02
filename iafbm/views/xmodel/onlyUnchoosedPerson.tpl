<?php
/**
 * This template is made for create the where clause to the evaluation
 */

// Creates conditions SQL strings
$conditions = array();
$searchFields = array('personnes.prenom','personnes.nom');
foreach ($d['where'] as $where) {
    if(in_array("{$where['table']}.{$where['field']}", $searchFields)) 
    $conditions[] = " {$where['table']}.{$where['field']} LIKE '{$where['value']}'";
}

// Issues WHERE statement
$idsToAvoid = (@$d['model']->params['idsToAvoid']) ? 'AND personnes.id NOT IN ('.$d['model']->params['idsToAvoid'].')' : '';
print 'WHERE personnes.actif = 1 AND ('.implode("\nOR ", $conditions).') '.$idsToAvoid;
