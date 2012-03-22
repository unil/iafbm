<style>
#<?php echo $d['dom_id'] ?> {
    padding: 0px !important;
    height: <?php echo $d['height']?>px !important;
    <?php if (isset($d['width'])): ?>
      width: <?php echo $d['width'] ?>px !important;
    <?php endif ?>
}
</style>

<div id="<?php echo $d['dom_id'] ?>"></div>

<script>
Ext.onReady(function() {
    mm = map_init();
});

function map_init(){
    var map = new OpenLayers.Map({
        div: "<?php echo $d['dom_id'] ?>",
        theme: null,
        _controls: [
            new OpenLayers.Control.TouchNavigation({ dragPanOptions: { enableKinetic: true }}),
            new OpenLayers.Control.ZoomPanel()
        ],
        layers: [
            new OpenLayers.Layer.OSM("OSM Layer")
        ],
        _restrictedExtent: new OpenLayers.Bounds(-180, -90, 180, 90), // TODO: Transform projection to EPSG:4326
        center: new OpenLayers.LonLat(933754.7374017, 5905219.0563776),
        zoom: 8
    });
    return map;
}
</script>
