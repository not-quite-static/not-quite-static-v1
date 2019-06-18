<?php

namespace nqs;

class router {

    private static $routes = [];

    public static function init($settings)
    {

        router::$routes = $settings;

    }

    private static $route = null;

    public static function getRoute()
    {

        if(router::$route != null)
        {
            return router::$route;
        }

        $route   = null;
        $matches = null;

        foreach (router::$routes as $value) {
                
            if($value['path'] == $_SERVER['REQUEST_URI'])
            {
        
                $route = $value;              
                break;
        
            }       

            if(@preg_match($value['path'], $_SERVER['REQUEST_URI'], $matches))
            {
    
                $route = $value;
                break;
    
            }
        
        }

        if($matches != null)
        {

            return array_merge($route, array('matches' => $matches));
        
        }

        // $route = pluginManager::hook("route", $route);

        router::$route = $route;

        return $route;

    }

    private static function isRegex ($route)
    {
    
        return preg_match("/^\/[\s\S]+\/$/", $route) ? true : false;
    
    }

}