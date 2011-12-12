<?php

class GenreModel extends iaModelMysql {

    var $table = 'genres';

    var $versioning = false;

    var $mapping = array(
        'id' => 'id',
        'genre' => 'genre',
        'genre_short' => 'genre_short',
        'intitule' => 'intitule',
        'intitule_short' => 'intitule_short'
    );

    var $order_by = array('id');

    var $primary = array('id');
}
