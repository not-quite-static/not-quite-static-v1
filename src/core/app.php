<?php
namespace nqs;

class app {

    public static function init()
    {

        config::init();

        router::init(config::getRoutes());
        render::init(config::getRender());
        
        pluginManager::hook("init");

    }

    public static function RemoteIP()
    {

        if (isset($_SERVER['HTTP_X_remote_ip']))
        {

            if(config::getTrustedProxies())
            {

                foreach(config::getTrustedProxies() as $CIDR)
                {
                    if(CIDR_check::match($_SERVER['HTTP_CLIENT_IP'], $CIDR))
                    {
                        return $_SERVER['HTTP_X_remote_ip'];
                    }
                }
            }


        }

        return $_SERVER['HTTP_CLIENT_IP'];

    }

    public static function run()
    {
        
        $route = router::getRoute();
        
        $_view = null;
        
        if($route == null)
            $_view = new view(config::get404());
        else
            $_view = new view($route);
        
        $_view->render();

    }


}