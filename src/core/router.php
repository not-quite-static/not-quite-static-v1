<?php

namespace nqs;

class router {

    private static $routes = [];

    public static function init($settings)
    {

        router::$routes = $settings;

    }

    public static function getRoute()
    {

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

        return $route;

    }

    private static function isRegex ($route)
    {
    
        return preg_match("/^\/[\s\S]+\/$/", $route) ? true : false;
    
    }

}