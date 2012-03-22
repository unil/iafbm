<style>
#<?php echo $d['dom_id'] ?> {
    padding: 0px !important;
    height: <?php echo $d['height']?>px !important;
    <?php if (isset($d['width'])): ?>
      width: <?php echo $d['width'] ?>px !important;
    <?php endif ?>
}

.olControlAttribution {
    top: 0;
}
</style>

<div id="<?php echo $d['dom_id'] ?>"></div>

<script>
Ext.onReady(function() {
    mm = map_init();
});

function map_init(){
    // Layers
    var layer_osm = new OpenLayers.Layer.OSM("OSM Layer");
    var layer_data = new OpenLayers.Layer.Vector("Data", {
        strategies: [new OpenLayers.Strategy.Fixed()], // TODO: switch to BBOX strategy
        //strategies: [new OpenLayers.Strategy.BBOX()],
        protocol: new OpenLayers.Protocol.HTTP({
            url: "<?php echo xUtil::url('api/personnes?xmethod=map') ?>",
            format: new OpenLayers.Format.GeoJSON()
        })
    });
    // Map
    var map = new OpenLayers.Map({
        div: "<?php echo $d['dom_id'] ?>",
        theme: null,
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        //projection: new OpenLayers.Projection("EPSG:900913"),
        //unit: "m",
        //numZoomLevels: 18,
        //maxResolution: 156543.0339,
        //maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")),
        layers: [layer_osm, layer_data],
        controls: [
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoom(),
            new OpenLayers.Control.ZoomBox(),
            new OpenLayers.Control.Attribution(),
            new OpenLayers.Control.MousePosition({
                emptyString: '',
                numDigits: 2
            })
        ],
        center: new OpenLayers.LonLat(933754.7374017, 5905219.0563776),
        zoom: 8
    });
    // Data layer features popup
    var control_select = new OpenLayers.Control.SelectFeature(layer_data);
    layer_data.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect
    });
    map.addControl(control_select);
    control_select.activate();
    function onFeatureSelect(event) {
        var feature = event.feature;
        var content = Ext.String.format(
            [
                "<h2>{0} {1}</h2>",
                "<div>{2}<br/>{3} {4}</div>"
            ].join(''),
            feature.attributes.personne_prenom,
            feature.attributes.personne_nom,
            feature.attributes.adresse_rue.replace(/\n/g, '<br/>'),
            feature.attributes.adresse_npa,
            feature.attributes.adresse_lieu
        );
        if (content.search("<script") != -1) content = content.replace(/</g, "&lt;")
        popup = new OpenLayers.Popup.FramedCloud(
            "chicken", // Popup id
            feature.geometry.getBounds().getCenterLonLat(), // ?
            null,      // Popup size, eg: new OpenLayers.Size(100,100)
            content,   // Popup HTML content
            null,      // Popup anchor (auto if null)
            true,      // Popup close box display
            function() { control_select.unselectAll() } // Popup close callback
        );
        feature.popup = popup;
        map.addPopup(popup);
    }
    function onFeatureUnselect(event) {
        var feature = event.feature;
        if (feature.popup) {
            map.removePopup(feature.popup);
            feature.popup.destroy();
            delete feature.popup;
        }
    }
    // Returns map instance
    return map;
}
</script>
