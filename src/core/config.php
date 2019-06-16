<?php
namespace nqs;

use Symfony\Component\Yaml\Yaml;

class config {

    private static $config;

    private static $configV = 1;

    public static function init()
    {

        config::$config = Yaml::parse(file_get_contents( dirname(dirname(dirname(__FILE__))) . "/config.yml"));

        if(config::$config != config::$configV)
        {

            config::upgradeConfig();

        }

        if(isset(config::$config['globals']))
            foreach (config::$config['globals'] as $file) 
                database::load($file);
        
    }

    private static function upgradeConfig()
    {

    }

    public static function get404()
    {

        return config::$config['error_404'];

    }

    public static function getRoutes()
    {

        return config::$config['routes'];

    }

    public static function getRender()
    {

        return config::$config['render'];

    }

}