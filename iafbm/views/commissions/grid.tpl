<?php
//create the dataset for the storeFilter "évaluateurs"
$presidentsData = "";
foreach($d['presidents'] as $president){
    $presidentsData .= "{'president':'{$president}'},";
}

$data = array_merge($d, array(
    'columns' => 'iafbm.columns.Commission',
    //'store-params' => array('actif' => 1, 'xorder' => 'ASC', 'xorder_by' => 'evaluation_etat_id'),
    'filters' => array(
        'gridId' => 'commission',
        'items' => array(
            array(
                'itemId' => 'type',
                'fieldLabel' => 'Type',
                'store' => 'new iafbm.store.CommissionType()',
                'displayField' => 'racine',
                'valueField' => 'id',
                'filterColumn' => 'commission_type_id'
            ),
            array(
                'itemId' => 'etat',
                'fieldLabel' => 'Etat',
                'store' => "
                        new iafbm.store.CommissionEtat()
                ",
                'displayField' => 'nom',
                'valueField' => 'id',
                'filterColumn' => 'commission_etat_id'
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
                'itemId' => 'filterPresident',
                'fieldLabel' => 'Président',
                'store' => "Ext.create('Ext.data.Store', {
                    fields: ['president'],
                    data : [".$presidentsData."]
                });",
                'displayField' => 'president',
                'valueField' => 'president',
                'filterColumn' => '_presidents',
                'specialFilter' => "(function(rec, id){
                    console.log(rec.data._president+' -> '+itemValue);
                    president = rec.data._president;
                    
                    // check if the filter parameter is in each row of the store
                    // this filter is local only
                    
                    if(president.indexOf(itemValue) !== -1){
                        return true;
                    }else{
                        return false;
                    }
                })"
            )
        )
    )
));
echo xView::load('common/extjs/grid', $data, $this->meta)->render();
?>