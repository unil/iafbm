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

.olControlScaleLine {
    bottom: -5px;
}

.olControlScaleLineBottom {
    visibility: hidden !important;
}

#chicken_FrameDecorationDiv_4 {
    visibility: hidden;
}
.olPopup {
    top: 25px;
}
</style>

<style>
    form input { border: 1px solid gray; height: 19px; }
    #map_query_text { width: 830px }
    #map_query_button,
    #map_query_reset_button { width: 25px }
</style>
<form onsubmit="javascript:map_query();return false;" style="position:absolute;top:100px">
    <label for="map_query_text">Recherche:</label>
    <input type="text" name="map_query_text" id="map_query_text"/>
    <input type="submit" name="map_query_button" id="map_query_button" value=">"/>
    <input type="reset" name="map_query_reset_button" id="map_query_reset_button" value="X" onclick="form.reset();map_query()"/>
</form>

<script>
// Some logic for the cluster checkbox toggler
function cluster_toggle(state) {
    var layer = mm.getLayersByName('Data').pop(),
        cluster = layer.strategies[0];
    state ? cluster.activate() : cluster.deactivate();
    layer.refresh(); 
}
</script>
<input type="checkbox" id="map_cluster_checkbox" name="map_cluster_checkbox" onclick="cluster_toggle(this.checked)"/>
<label for="map_cluster_checkbox">Cluster, please</label>

<div id="<?php echo $d['dom_id'] ?>"></div>

<script>
Ext.onReady(function() {
    mm = map_init();
});

function map_init(){
    // StyleMaps
    var stylemap_hover = new OpenLayers.StyleMap({
        "default": new OpenLayers.Style({
            pointRadius: "${radius}", // Sized according to 'radius' context
            strokeWidth: "${width}",  // Sized according to 'width' context
            fillColor: "${color}",
            strokeColor: "#ff9933",
            fillOpacity: 0.8,
            graphicZIndex: 1,
            label: "${count}",
            labelWeight: "bold",
            //fontWeight: "bold"
        },{
            // Rules for dynamic styling (cannot be used with clustering since features are in feature.cluster[]
            ____rules: [
                new OpenLayers.Rule({
                    filter: new OpenLayers.Filter.Comparison({
                        type: OpenLayers.Filter.Comparison.EQUAL_TO,
                        property: "personne_genre_id",
                        value: 1
                    }),
                    symbolizer: {
                        fillColor: "#8888ff"
                    }
                }),
                new OpenLayers.Rule({
                    filter: new OpenLayers.Filter.Comparison({
                        type: OpenLayers.Filter.Comparison.EQUAL_TO,
                        property: "personne_genre_id",
                        value: 2 
                    }),
                    symbolizer: {
                        fillColor: "#ff8888"
                    }
                })
            ],
            // Context for feature values processing
            context: {
                count: function(feature) {
                    return (feature.cluster) ? feature.attributes.count : '';
                },
                width: function(feature) {
                    return (feature.cluster) ? 2 : 1;
                },
                radius: function(feature) {
                    var pix = 5;
                    if(feature.cluster) {
                        pix = Math.min(Math.round(feature.attributes.count/15), 7) + pix;
                    }
                    return pix;
                },
                color: function(feature) {
                    return (feature.cluster) ? "#ffcc66" : "yellow";
                }
            }
        }),
        "select": new OpenLayers.Style({
            fillColor: "#66ccff",
            strokeColor: "#3399ff",
            graphicZIndex: 2
        })
    }); 
    // Layers
    var layer_osm = new OpenLayers.Layer.OSM("OSM Layer");
    var layer_data = new OpenLayers.Layer.Vector("Data", {
        strategies: [
            new OpenLayers.Strategy.Cluster({
                features: [], // For the 'Cluster, please' checkbox to make an impression :)
                distance: 20,
                threshold: 2
            }),
            new OpenLayers.Strategy.Fixed() // TODO: switch to BBOX strategy
            //new OpenLayers.Strategy.BBOX()
        ],
        protocol: new OpenLayers.Protocol.HTTP({
            format: new OpenLayers.Format.GeoJSON(),
            url: "<?php echo xUtil::url('api/personnes') ?>",
            params: {
                xmethod: 'map',
                xquery: null
            }
        }),
        styleMap: stylemap_hover,
        rendererOptions: {zIndexing: true}
    });
    // Map
    var map = new OpenLayers.Map({
        // DOM configuration
        div: "<?php echo $d['dom_id'] ?>",
        theme: null,
        // Spatial configuration
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        //projection: new OpenLayers.Projection("EPSG:900913"),
        unit: "m",
        numZoomLevels: 18,
        //maxResolution: 156543.0339,
        //maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913")),
        layers: [layer_osm, layer_data],
        controls: [
            // Navigation
            new OpenLayers.Control.Navigation(),
            new OpenLayers.Control.PanZoom(),
            new OpenLayers.Control.ZoomBox(),
            // Information display
            new OpenLayers.Control.ScaleLine({geodesic: true}),
            new OpenLayers.Control.Attribution(),
            new OpenLayers.Control.MousePosition({
                emptyString: '',
                numDigits: 2
            }),
            // Layer controls
            new OpenLayers.Control.SelectFeature(layer_data, {
                id: "control:select:data",
                autoActivate: true,
                //hover: true
            })
        ],
        // Initial state configuration
        center: new OpenLayers.LonLat(933754.7374017, 5905219.0563776),
        zoom: 8
    });
    // Data layer features popup
    layer_data.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect
    });
    open_form_personne = function(id) {
        new Ext.ia.window.Popup({
            title: 'Détails',
            item: new iafbm.form.Personne({
                frame: false,
                fetch: {
                    model: iafbm.model.Personne,
                    id: id
                },
                listeners: {
                    beforesave: function() {
                        popup.destroy();
                        layer_data.refresh({force:true});
                        // Restores closed popup
                        // TODO: Put this logic in layer.afterrefresh event + remove the listener at once (for it is to be run only once).
                        //var feature = layer_data.getFeaturesByAttribute('personne_id', '18').pop();
                        //var selectControl = map.getControl('control:select:data'); 
                        //selectControl.select(feature);
                        
                        
                    }
                }
            })
        });
    }
    function onFeatureSelect(event) {
        var feature = event.feature;
        // Zooms in on cluster feature click
        var zoomable = map.getZoom() < map.numZoomLevels;
        if (feature.cluster && zoomable) {
            // Creates Pixel from feature centroid 
            var point = feature.geometry.getCentroid(),
                pixel = map.getPixelFromLonLat(new OpenLayers.LonLat(point.x, point.y));
            // Uses Navigation Control to zoom in, keeping feature under mouse
            var navigation = map.getControlsByClass("OpenLayers.Control.Navigation").pop();
            navigation.wheelChange({xy: pixel}, +1);
            // Bad way below, causing map center to jump around
            //map.zoomTo(map.getZoom()+1);
            return;
        }
        // Displays information popup, caring about cluster features
        var features = !feature.cluster ? [feature] : feature.cluster;
        var content = [];
        for (i in features) content.push(Ext.String.format(
            [
                "Adresse {5}",
                "<hr/>",
                "<h2>{0} {1}</h2>",
                "<div>{2}<br/>{3} {4}</div>",
                '<a href="javascript:open_form_personne({7})">Détail</a>',
                "<br/>"
            ].join(''),
            features[i].attributes.personne_prenom,
            features[i].attributes.personne_nom,
            features[i].attributes.adresse_rue.replace(/\n/g, '<br/>'),
            features[i].attributes.adresse_npa,
            features[i].attributes.adresse_lieu,
            features[i].attributes.adresse_type_nom.toLowerCase(),
            "<?php echo xUtil::url('personnes') ?>",
            features[i].attributes.personne_id
        ));
        content = content.join('<br/>');
        if (content.search("<script") != -1) content = content.replace(/</g, "&lt;")
        popup = new OpenLayers.Popup.FramedCloud(
            "chicken", // Popup id
            feature.geometry.getBounds().getCenterLonLat(), // ?
            null,      // Popup size, eg: new OpenLayers.Size(100,100)
            content,   // Popup HTML content
            null,      // Popup anchor (auto if null)
            true,      // Popup close box display
            function() { this.map.getControlsBy('id', 'control:select:data').pop().unselectAll() } // Popup close callback
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

function map_query() {
    var text = document.getElementById('map_query_text').value,
        layer = mm.layers[1];
    layer.protocol.params.xquery = text;
    layer.refresh();
}
</script>
