<?php

class GenreModel extends xModelMysql {

    var $table = 'genres';

    var $mapping = array(
        'id' => 'id',
        'genre' => 'genre',
        'genre_short' => 'genre_short',
        'intitule' => 'intitule',
        'intitule_short' => 'intitule_short'
    );

    var $order_by = array('genre');

    var $primary = array('id');
}
