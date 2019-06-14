<?php

use Symfony\Component\Yaml\Yaml;

use nqs\render;
use nqs\database;
use nqs\router;

$config = Yaml::parse(file_get_contents( dirname(dirname(__FILE__)) . "/config.yml"));

if($config['globals'])
{
    foreach ($config['globals'] as $file) {
        database::load($file);
    }
}

render::init($config);

router::init($config);

$route = router::getRoute();

if($route == null)
{

    echo render::render($config['error_404'], database::$data);
    return;

}

if (isset($route['matches']))
{
    for ($i = 0; $i < sizeof($route['matches']); $i++) { 

        database::add([ "parm_". $i => $route['matches'][$i] ]);

    }
}

if(isset($route['databases']))
{

    for ($i = 0; $i < sizeof($route); $i++) { 

        database::load($route['databases'][$i]);

    }

}

echo render::render($route['view'], database::$data);



