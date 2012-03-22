<?php

class CommonMapMapView extends xView {

    function init() {
        // Include OpenLayers library
        $this->meta = xUtil::array_merge($this->meta, array(
            'js' => array(xUtil::url('a/js/OpenLayers/lib/OpenLayers.js'))
        ));
        // Sets default values
        if (!isset($this->data['width'])) $this->data['width'] = '600';
        if (!isset($this->data['height'])) $this->data['height'] = '400';
        if (!isset($this->data['dom_id'])) $this->data['dom_id'] = 'map-'.md5(mktime().rand());
return;
        // Computes Google Maps API key according the current domain
        preg_match('/\w*\.\w*$/', $_SERVER['SERVER_NAME'], $m);
        $domain = str_replace('.', '_', @$m[0]);
        $google_maps_key = @xContext::$config->site->apikeys->googlemaps->$domain;
        if (!$google_maps_key) throw new xException('Could not find google maps api key in config', 500);
        // Adds js meta
        $this->add_meta(array(
            'js' => array(
                "http://maps.google.com/maps?file=api&amp;v=2&amp;key={$google_maps_key}",
                xUtil::url('a/js/openlayers/lib/OpenLayers.js')
            )
        ));
    }
}

?>
