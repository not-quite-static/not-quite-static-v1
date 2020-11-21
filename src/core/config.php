<?php
namespace nqs;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class config {

    private static $config;

    private static $configV = 1;

    public static function init()
    {

        $path = dirname(dirname(dirname(__FILE__))) . "/config.yml";

        try {
            config::$config = Yaml::parseFile($path);
        } catch (ParseException $th) {
            echo "can't read config";
            echo $th->getMessage();
            die();
        }

        if(isset(config::$config['globals']))
            foreach (config::$config['globals'] as $file) 
                database::load($file);
        
    }

    public static function getConfig()
    {
        return config::$config;
    }

    public static function isCache()
    {
        return config::$config['cache'];
    }

    public static function getRoutes()
    {
        return config::$config['routes'];
    }

    public static function getCaseSensitive() {
        return config::$config['case_sensitive'];
    }

    public static function getRender()
    {
        return config::$config['render'];
    }

    public static function getTrustedProxies()
    {
        return isset(config::$config['trusted_proxies'])? config::$config['trusted_proxies'] : false;
    }

}