<?php

use Symfony\Component\Yaml\Yaml;

use nqs\render;
use nqs\database;

$config = Yaml::parse(file_get_contents( dirname(dirname(__FILE__)) . "/config.yml"));

if($config['globals'])
{
    foreach ($config['globals'] as $file) {
        database::load($file);
    }
}

render::init($config);

foreach ($config['routes'] as $route) {
    //  || $route['path'] == parse_url($_SERVER['REQUEST_URI'])
    if(@preg_match($route['path'], $_SERVER['REQUEST_URI'], $matches)  || $route['path'] == $_SERVER['REQUEST_URI'])
    {

        $url_parms = [];

        if ($matches != null)
        {
            for ($i=0; $i < sizeof($matches); $i++) { 

                database::add([ "parm-"+$i => $matches[$i] ]);

            }
        }

        if(isset($route['databases']))
        {

            for ($i=0; $i < sizeof($route); $i++) { 

                database::load($route['databases'][$i]);

            }

        }

        echo render::render($route['view'], database::$data);

    }
}

