<?php
/**
 * This template is made for create the where clause to the evaluation adding toolbar
 * Select only searched persons with $activityNameIdSearchFor
 */

$activityNameIdSearchFor = '1,2,4,5,11,14,15,16,17,22';

// Creates conditions SQL strings
$conditions = array();
$searchFields = array('personnes.prenom','personnes.nom');
foreach ($d['where'] as $where) {
    if(in_array("{$where['table']}.{$where['field']}", $searchFields)) 
    $conditions[] = " {$where['table']}.{$where['field']} LIKE '{$where['value']}'";
}

// Issues WHERE statement
$extraCondition = 'AND activites_noms.id IN ('.$activityNameIdSearchFor.')';
print 'WHERE personnes.actif = 1 AND ('.implode("\nOR ", $conditions).') '.$extraCondition;
