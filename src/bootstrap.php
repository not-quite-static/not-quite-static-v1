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

$is404 = true;

foreach ($config['routes'] as $route) {
    //  || $route['path'] == parse_url($_SERVER['REQUEST_URI'])
    if(@preg_match($route['path'], $_SERVER['REQUEST_URI'], $matches)  || $route['path'] == $_SERVER['REQUEST_URI'])
    {

        if ($matches != null)
        {
            for ($i=0; $i < sizeof($matches); $i++) { 


                database::add([ "parm_". $i => $matches[$i] ]);

            }
        }

        if(isset($route['databases']))
        {

            for ($i=0; $i < sizeof($route); $i++) { 

                database::load($route['databases'][$i]);

            }

        }

        echo render::render($route['view'], database::$data);
        $is404 = false;
        break;
    }
}

if($is404)
{
    echo render::render($config['error_404'], database::$data);
}


