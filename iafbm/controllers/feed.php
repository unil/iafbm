<?php

class FeedController extends iaExtRestController {

    /**
     * Determines supported events (eg. events that can be displayed)
     * and returns an array structure:
     * <code>
     * array(
     *     'modelname1' => array('op1', 'op2', 'op3'),
     *     'person' => array('put', 'post'),
     *     ...
     * )
     * </code>
     */
    protected function supported_events() {
        $scan = function($path) {
            return array_diff(scandir($path), array('.', '..'));
        };
        //
        $supported = array();
        // Scans for supported models
        $models = preg_replace('/\.php$/', null, $scan(xContext::$basepath.'/views/feed/events'));
        // Scans for supported operations per models
        foreach ($models as $model) {
            $operations = preg_replace('/\.tpl$/', null, $scan(xContext::$basepath."/views/feed/events/{$model}"));
            $supported[$model] = $operations;
        }
        return $supported;
    }

    function defaultAction() {
        $supported = $this->supported_events();
        // Fetches latest (supported) events
        $versions = xModel::load('version', array(
            'model_name' => array_keys($supported),
            'xlimit' => isset($this->params['xlimit']) ? $this->params['xlimit'] : 20,
            'xorder_by' => 'created',
            'xorder' => 'DESC'
        ))->get();
        // Fetches events related data
        $events = array();
        foreach ($versions as $version) {
            $event = array();
            // Adds version data to event
            $event['version'] = $version;
            // Adds version deltas data to event
            $event['deltas'] = xModel::load('version_data', array(
                'version_id' => $version['id'],
                'xjoin' => ''
            ))->get();
            // Adds related entity data to event
            $event['entity'] = xModel::load($version['model_name'], array(
                $version['id_field_name'] => $version['id_field_value'],
                'actif' => array(0,1)
            ))->get(0);
            // Adds related foreign entity data to event (if applicable)
            /*
            $foreign_mapping = xModel::load($version['model_name'])->foreign_mapping();
            foreach ($event['deltas'] as $delta) {
                if (in_array($delta['field_name'], $foreign_mapping)) {
                    $model = ...;
                    $event['foreigns'][$model] = xModel::load($model, y)->get();
                }
            }
            */
            $events[] = $event;
        }
        // Renders an HTML line per event
        $lines = array();
        foreach ($events as $event) {
            $model = $event['version']['model_name'];
            $operation = $event['version']['operation'];
            try {
                $lines[] = array(
                    'event' => xView::load("feed/events/{$model}/{$operation}", $event)->render(),
                    'info' => xView::load('feed/info', $event)->render(),
                    // TODO: details about modified fields | details <> delta, what's the difference?
                    //'details' => null, // eg: Commission n°123 modifiée [avec décision CDir au "20.08.2012"] [avec Commentaire à "En attente de..."] [avec Préavis CDir supprimé]
                    //'delta' => xView::load('feed/delta', $event)->render()
                );
            } catch (xException $e) {
                if ($e->status == 404) null;
                else throw $e;
            }
        }
        // Renders an HTML event list
        return xView::load('feed/events', $lines)->render();
    }
}