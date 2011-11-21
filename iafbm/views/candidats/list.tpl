<script type="text/javascript">

// Tunes Candidat columns
var columns = Ext.Array.clone(iafbm.columns.Candidat);
// Removes details action
columns.shift();
// Prepends detail and 'commission_nom' columns
columns.splice(0, 0, {
    header: "Commission",
    dataIndex: 'commission_nom',
    width: 300
});
columns.splice(0, 0, {
    xtype: 'ia-actioncolumn-redirect',
    width: 25,
    text: 'Détails commission',
    tooltip: 'Détails commission',
    getLocation: function(grid, record, id) {
        return x.context.baseuri+'/commissions/'+id+'#candidat'
    }
});

</script>



<?php

$data = array(
    'title' => 'Candidats',
    'id' => 'candidats',
    'model' => 'Candidat',
    'columns' => 'columns',
    'store-params' => array('xjoin' => 'commission'),
    'toolbarButtons' => array('search'),
    'editable' => false
);
echo xView::load('common/extjs/grid', $data, $this->meta)->render();

?>