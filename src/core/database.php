<?php
namespace nqs;

class database {

    public static $data = [];

    public static function load($path)
    {

        database::add(datafile::read($path));

    }

    public static function add($data)
    {

        database::$data = array_merge(database::$data, $data);

    }

}