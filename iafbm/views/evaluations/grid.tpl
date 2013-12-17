<?php
//create the dataset for the storeFilter "année"
$yearsData = "";
foreach($d['endYears'] as $year){
    $yearsData .= "{'year':'{$year}'},";
}

//create the dataset for the storeFilter "évaluateurs"
$evaluatorsData = "";
foreach($d['evaluators'] as $evaluator){
    $evaluatorsData .= "{'evaluateur':'{$evaluator}'},";
}

$data = array_merge($d, array(
    'columns' => 'iafbm.columns.Evaluation',
    'store-params' => array('actif' => 1, 'xorder' => 'ASC', 'xorder_by' => 'evaluation_etat_id'),
    'filters' => array(
        'gridId' => 'évaluation',
        'items' => array(
            array(
                'itemId' => 'type',
                'fieldLabel' => 'Type',
                'store' => 'new iafbm.store.EvaluationType()',
                'displayField' => 'type',
                'valueField' => 'id',
                'filterColumn' => 'evaluation_type_id'
            ),
            array(
                'itemId' => 'titre',
                'fieldLabel' => 'Titre académique',
                'store' => "
                        new iafbm.store.ActiviteNom({
                            params: {
                                'id[]': [1,2,4,5,11,14,15,16,17,22],
                            }
                        })
                ",
                'displayField' => 'abreviation',
                'valueField' => 'abreviation',
                'filterColumn' => 'activite_nom_abreviation'
            ),
            array(
                'itemId' => 'section',
                'fieldLabel' => 'Section',
                'store' => 'new iafbm.store.Section()',
                'displayField' => 'code',
                'valueField' => 'id',
                'filterColumn' => 'section_id'
            ),
            array(
                'itemId' => 'annee',
                'fieldLabel' => 'Année',
                'store' => "Ext.create('Ext.data.Store', {
                    fields: ['year'],
                    data : [".$yearsData."]
                });",
                'displayField' => 'year',
                'valueField' => 'year',
                'filterColumn' => 'date_periode_fin',
                'specialFilter' => "(function(rec, id){
                    var dateToFilter = new Date(itemValue, 1,1),
                        date = rec.data.date_periode_fin;
                    
                    if(date != null){
                        if(dateToFilter.getFullYear() == date.getFullYear()){
                            return true;
                        }else{
                            return false;
                        }
                    }
                    
                })"
            ),
            array(
                'itemId' => 'filterEvaluateur',
                'fieldLabel' => 'Évaluateurs',
                'store' => "Ext.create('Ext.data.Store', {
                    fields: ['evaluateur'],
                    data : [".$evaluatorsData."]
                });",
                'displayField' => 'evaluateur',
                'valueField' => 'evaluateur',
                'filterColumn' => '_evaluateurs',
                'specialFilter' => "(function(rec, id){
                    evaluateurs = rec.data._evaluateurs;
                    // check if the filter parameter is in each row of the store
                    // this filter is local only
                    if(evaluateurs.indexOf(itemValue) !== -1){
                        return true;
                    }else{
                        return false;
                    }
                })"
            )
        )
    ),
    'toolbarButtons' => array('delete', 'save', 'searchPeople', 'search'),
    'toolbarButtonsParams' => array(
        'searchPeople' => array(
            'store' => "new iafbm.store.PersonneActivite({
                params: {
                    xreturn: 'personne_id, personne_nom, personne_prenom, activite_id, activite_nom_abreviation, section_id, section_code, debut, fin',
                    xwhere: 'evaluateAllowed'
                }
            })"
        )
    ),
    'makeData' => array(
        'keyValue' => array(
            'personne_id' => 'personne_id',
            'personne_nom' => 'personne_nom',
            'personne_prenom' => 'personne_prenom',
            'section_id' => 'section_id',
            'section_code' => 'section_code',
            'activite_id' => 'activite_id',
            'activite_nom_abreviation' => 'activite_nom_abreviation',
        ),
        'value' => array(
            '_mandat' => "(Ext.Date.format(new Date(record.get('debut')),'d.m.Y') + ' - ' + Ext.Date.format(new Date(record.get('fin')),'d.m.Y'))",
            'evaluation_etat_id' => 1,
        )
    ),
));
echo xView::load('common/extjs/grid', $data, $this->meta)->render();
?>