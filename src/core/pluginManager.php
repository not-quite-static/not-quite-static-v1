<?php
namespace nqs;

use nqs\iplugin;

class pluginManager {

    private static $plugins;

    public static function preinit()
    {

        $plugins = glob(dirname(dirname(dirname(__FILE__))) . '/plugins/*' , GLOB_ONLYDIR);

        foreach ($plugins as $value) {
            if(file_exists($value . "/main.php"))
                include $value . "/main.php";
        }

        pluginManager::$plugins = array_filter(
            get_declared_classes(), 
            function ($className) {
                return in_array('iplugin', class_implements($className));
            }
        );


        foreach(pluginManager::$plugins as $plugin)
        {

            $plugin->preinit();

        }

    }

    public static function init()
    {
        
        foreach(pluginManager::$plugins as $plugin)
        {

            $plugin->init();

        }

    }

    public static function postinit()
    {

        foreach(pluginManager::$plugins as $plugin)
        {

            $plugin->postinit();

        }

    }

}