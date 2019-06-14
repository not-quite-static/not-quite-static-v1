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

function isRegex ($route)
{

    return @preg_match("/^\/[\s\S]+\/$/", $route) ? true : false;

}

function is404 ($route)
{

    $isregex = isRegex($route);
    $isroute = false;
    if($route['path'] == $_SERVER['REQUEST_URI'])
    {
        $isroute = true;   
    }

    $found = ($isroute or $isregex);

    return $found;

};

$routes  = array_filter($config['routes'], "is404");
$route   = null;
$matches = null;
foreach ($routes as $value) {


    if($value['path'] == $_SERVER['REQUEST_URI'])
    {

        $route = $value;
        break;

    }

    if(isRegex($value['path']))
    {
        if(@preg_match($value['path'], $_SERVER['REQUEST_URI'], $matches))
        {

            $route = $value;
            break;

        }
    }

}


if($route == null)
{

    echo render::render($config['error_404'], database::$data);
    return;

}

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



