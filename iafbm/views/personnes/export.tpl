<?php
    // Default checked fields
    $fields_labels = xController::load('personnes')->export_fields_labels;
    $fields_checked = array(
        'nom',
        'prenom',
        'personne_denomination_nom',
        'personne_type_nom',
        'adresse_adresse_type_nom',
        'adresse_rue',
        'adresse_npa',
        'adresse_lieu',
        'adresse_pays_nom',
        'personne_email_adresse_type_nom',
        'personne_email_email'
    );
    // Default operating system (detection, or defaults to modes 1st option)
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/Linux/', $agent)) $os = 'linux';
    elseif(preg_match('/Win/', $agent)) $os = 'windows';
    elseif(preg_match('/Mac/', $agent)) $os = 'mac';
    else $os = 'Unknown';
    $mode_detected = in_array($os, array_keys($d['modes'])) ?
        $os : @array_shift(array_keys($d['modes']));
?>

<h1>Exportation des personnes (CSV)</h1>

<div>
  <form id="export-form" method="post" style="padding:10px;line-height:166%">
  <h2>Sélectionnez votre système</h2>
<?php foreach ($d['modes'] as $mode => $x): ?>
<?php
    $id = "mode-{$mode}";
    $checked = ($mode_detected==$mode) ? ' checked="checked"' : null;
?>
    <div>
      <input type="radio" name="mode" id="<?php echo $id ?>" value="<?php echo $mode ?>" <?php echo $checked ?>/>
      <label for="<?php echo $id ?>"><?php echo ucwords($mode) ?></label>
    </div>
<?php endforeach ?>
  <br/>
  <h2>Sélectionnez les champs à exporter:</h2>
<?php foreach ($fields_labels as $field => $label): ?>
<?php
    $id = "export-fields-{$field}";
    $checked = in_array($field, $fields_checked) ? ' checked="checked"' : null;
?>
    <div id="fields-list">
      <input type="checkbox" id="<?php echo $id ?>" name="fields[]" value="<?php echo $field ?>"<?php echo $checked ?>/>
      <label for="<?php echo $id ?>"><?php echo $label ?></label>
    </div>
<?php endforeach ?>
    <br/>
    <a href="javascript:void(0)" id="select-all-fields">Sélectionner tous les champs</a>
    <br/><br/> 
    <div id="filter1"></div><br />
    <div id="filter2"></div>    
    <br/><br/>
    <input type="button" id="do-export" value="Télécharger le fichier" style="padding:10px"/>
  </form>
</div>

<script>
// Select all fields feature
Ext.get('select-all-fields').on('click', function() {
    Ext.select('#fields-list input').each(function(el) {
        el.dom.checked="checked"
    });
});
// Shows spinner on form submit
Ext.get('do-export').on('mouseup', function() {
    var validation = validFilters();
    if(validation.valid){
        Ext.get('do-export').replaceWith({
            tag: 'div',
            style: 'font-weight:bold',
            children: [{
                tag: 'img',
                src: '<?php echo u('a/img/icons/spinner-32x32.gif') ?>',
                style: 'width:32px;height:32px'
            }, {
                tag: 'span',
                style: 'position:relative; left:15px; bottom:12px',
                html: 'Patientez pendant la préparation des données'
            }]
        });
        Ext.get('export-form').dom.submit();
    }else{
        Ext.Msg.show({
            title: validation.title,
            msg: validation.msg,
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.QUESTION,
        });
    }
});

var filtre1 = Ext.create('Ext.FormPanel', {
    renderTo: 'filter1',
    id:"toto",
    frame: true,
    title: 'Filtre: Personnes active entre deux dates',
    bodyPadding: '5px 5px 5px',
    width: 260,
    fieldDefaults: {
        labelWidth: 50,
        msgTarget: 'side',
        autoFitErrors: false
    },
    defaults: {
        width: 190
    },
    defaultType: 'ia-datefield',
    items: [
        {
            fieldLabel: 'Du',
            name: 'begin',
            id: 'field_begin',
            listeners: {
                focus: function (t,n,o) {
                    var date = Ext.getCmp('field_date'),
                        panel = date.up();
                    this.setReadOnly(false);
                    this.next().setReadOnly(false);
                    date.setValue('');
                    date.setReadOnly(true);
                    panel.setWidth(250);
                }
            }
        },{
            fieldLabel: 'Au',
            name: 'end',
            id: 'field_end',
            listeners: {
                focus: function (t,n,o) {
                    var date = Ext.getCmp('field_date'),
                        panel = date.up();
                    this.setReadOnly(false);
                    this.prev().setReadOnly(false);
                    date.setValue('');
                    date.setReadOnly(true);
                    panel.setWidth(250);
                }
            }
        }
    ]
});

var filtre2 = Ext.create('Ext.FormPanel', {
    renderTo: 'filter2',
    id:"toto2",
    frame: true,
    title: 'Filtre: Personnes active à cette dates',
    bodyPadding: '5px 5px 5px',
    width: 260,
    fieldDefaults: {
        labelWidth: 50,
        msgTarget: 'side',
        autoFitErrors: false
    },
    defaults: {
        width: 190
    },
    defaultType: 'ia-datefield',
    items: [
        {
            fieldLabel: 'Actif le',
            name: 'date',
            id: 'field_date',
            listeners: {
                focus: function (t,n,o) {
                    var begin = Ext.getCmp('field_begin'),
                        end = Ext.getCmp('field_end'),
                        panel = begin.up();
                    this.setReadOnly(false);
                    begin.setValue(''); end.setValue('');
                    begin.setReadOnly(true); end.setReadOnly(true);
                    panel.setWidth(250);
                }
            }
        }
    ]
});

function validFilters(){
    var filter1 = {used:false, errors:false},
        filter2 = {used:false, errors:false},
        msgTitle = "Erreur dans le(s) filtre(s)";
        msg = "";
    
    //Check filter 1
    var begin = Ext.getCmp('field_begin').getValue(),
        end = Ext.getCmp('field_end').getValue(),
        date = Ext.getCmp('field_date').getValue();
    
    if(begin && end){     
        if(begin < end){
            filter1.used = true;            
        }else{
            filter1.errors = true;
            msg += "La première date du filtre \"Personne active entre deux dates\" doit être antérieur à la deuxième.<br />";
        }
    }
    
    if((!begin && end) || (begin && !end)){
        filter1.errors = true;
        msg += "Les deux dates doivent êtres entrées.<br />"
    }
    
    //Check filter 2
    if(date)
        filter2.used = true;
        
    //Check if two filters have been activated
    if(filter1.used && filter2.used){
        msg += "Les deux filtres sont remplis. Veuillez corriger";
        return {valid:false, title:msgTitle, msg:msg};
    }
    //Send response
    if(filter1.errors || filter2.errors)
        return {valid:false, title:msgTitle, msg:msg};
    else
        return {valid:true};
}

</script>