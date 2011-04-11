<?php
  $name = $d['name'];
  $map_id = ($d['name'] ? $d['name'] : md5(mktime().rand())).'_map';
?>
<input type="text" id="<?php echo $name ?>" name="<?php echo $name ?>" value="<?php echo $d['value'] ?>" style="width:33px;margin-bottom:0.5em"/>
<label style="font-weight:bold" id="placename"></label>
<br/>
<div id="<?php echo $map_id ?>" style="width:<?php echo $d['width'] ?>;height:<?php echo $d['height']?>"></div>

<input type="hidden" id="location" name="location" value=""/>


<style>
  .olLayerGooglePoweredBy {
    bottom: -8px;
  }
  .olLayerGoogleCopyright {
      bottom:0;
      left:65px;
  }
  .terms-of-use-link {
    bottom:10px;
    position:relative;
  }
  .olControlZoomPanel {
        left: auto;
        right: 23px;
        top: 8px;
  }
</style>

<script>
Ext.onReady(function() {
    map = map_init();
    init_zip_input(map);
});

function map_init() {
    OpenLayers.ImgPath = '<?php echo u('public/a/js/openlayers/theme/'); ?>';
    var olmap = new OpenLayers.Map('<?php echo $map_id ?>', { controls: [] });
    // Base layers
    var google_hybrid = new OpenLayers.Layer.Google("Satellite", {
        type: G_HYBRID_MAP,
        numZoomLevels: 22
    });
    var google_street = new OpenLayers.Layer.Google("Streets", {
        type: G_NORMAL_MAP,
        numZoomLevels: 22
    });
    olmap.addLayers([google_street, google_hybrid]);
    // Maker layer
    var markers_layer = new OpenLayers.Layer.Vector("Point", {
        displayInLayerSwitcher: false
    });
    olmap.addLayer(markers_layer);
    // Map controls
    //olmap.addControl(new OpenLayers.Control.LayerSwitcher());
    //olmap.addControl(new OpenLayers.Control.MousePosition());
    olmap.addControl(new OpenLayers.Control.ZoomPanel());
    olmap.addControl(new OpenLayers.Control.Navigation({zoomWheelEnabled:false}));
    // Map center
    center(olmap);
    return olmap;
}

function center(map) {
    map.setCenter(new OpenLayers.LonLat(8.305664, 46.71726), 6);
}

function init_zip_input(map) {
  var input = Ext.get('<?php echo $name ?>');
  var keyup = function(ev) {
eee=ev;
console.log(ev);
      var layer = map.getLayersByName('Point').pop();
      layer.removeFeatures(layer.features);
      Ext.get('placename').dom.innerHTML = '';
      var input = Ext.get('<?php echo $name ?>');
      if (typeof(input.zip_request)!='undefined') input.zip_request.abort();
      if (input.getValue().length < 4 || input.getValue().length > 4) {
          center(map);
          return;
      }
      input.zip_request = OpenLayers.loadURL(
        'http://ws.geonames.org/postalCodeLookupJSON', {
            formatted: true,
            maxRows: 1,
            country: 'CH',
            postalcode: input.getValue()
        },
        null, //caller,
        function(r) {
            var doc = new OpenLayers. Format.JSON().read(r.responseText);
            var city = doc.postalcodes[0];
            var lonlat = new OpenLayers.LonLat(city.lng, city.lat);

            Ext.get('placename').dom.innerHTML = city.adminName3;

            map.setCenter(lonlat, 11);
            function radius(distance, lonlat) {
                // Calculates radius from meters
                var dest = OpenLayers.Util.destinationVincenty(
                    lonlat,
                    0, // bearing?
                    distance
                )
                var d = Math.sqrt(Math.pow((dest.lon-lonlat.lon),2)+Math.pow((dest.lat-lonlat.lat),2));
                return d;
            }
            // Distance if applicable
            var distance = Ext.get('distance');
            if (distance) meters = distance.getValue()*1000;
            else meters = 20*1000;
            // Creates radius
            layer.addFeatures([
                new OpenLayers.Feature.Vector(
                    OpenLayers.Geometry.Polygon.createRegularPolygon(
                        new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat),
                        radius(meters, lonlat), // Radius (map units) -> conv meters->degrees
                        33 // Sides (n)
                    )
                ),
            ]);
            map.zoomToExtent(layer.getDataExtent());
            /*
            Creates WKT from point
            new OpenLayers.Format.WKT().write(
                new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat)
                )
            ));
            */
        },
        function(r) {
            center(map);
        }
      );
  }
  keyup();
  input.on('keyup', keyup);

  var distance = Ext.get('distance');
  if (distance) distance.on('keyup', keyup);
}


///////////////////////////////////////////////////////////////////////////////
/*
window.xmap = function() {
    //var private_function = function() { return 'Private function' };
    //var private_property = 'private property';
    return {
        config: {},
        plugins: {},
        map: null,
        init: function() {
            this.map = new OpenLayers.Map('<?php echo $map_id ?>', { controls: [] });
            // Base layers
            var google_hybrid = new OpenLayers.Layer.Google("Satellite", {
                type: G_HYBRID_MAP,
                numZoomLevels: 22
            });
            var google_street = new OpenLayers.Layer.Google("Streets", {
                type: G_NORMAL_MAP,
                numZoomLevels: 22
            });
            this.map.addLayers([google_street, google_hybrid]);
            // Maker layer
            var markers_layer = new OpenLayers.Layer.Vector("Point", {
                displayInLayerSwitcher: false
            });
            this.map.addLayer(markers_layer);
            // Map controls
            //olmap.addControl(new OpenLayers.Control.LayerSwitcher());
            //olmap.addControl(new OpenLayers.Control.MousePosition());
            this.map.addControl(new OpenLayers.Control.ZoomPanel());
            //olmap.addControl(new OpenLayers.Control.Navigation());
            // Map center
console.log(this, this.map)
            this.center();
        },
        center: function() {
console.log(this, this.map);
            this.map.setCenter(new OpenLayers.LonLat(8.305664, 46.71726), 6);
        }
    };
}();
window.addEvent('load', xmap.init);

//

window.xmap.plugins.radius = function() {
    if (window.xmap===undefined) { alert('xmap must be loaded before radius'); return; }
    return {
        init: function() {
            console.log(this);
        }
    }
}();
//window.addEvent('load', xmap.plugins.radius);
*/
</script>

