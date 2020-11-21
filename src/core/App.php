<?php
namespace nqs;

use \FastRoute\simpleDispatcher;
use \FastRoute\RouteCollector;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class App {

    private static $routes;
    private static $dispatcher;
    private static $twig_loader;
    private static $twig;

    public static function makeApp() {
        
        config::init();
        pluginManager::load();

        App::$routes = config::getRoutes();

        pluginManager::hook('router-init');

        if(config::isCache() == False)
            App::$dispatcher = \FastRoute\simpleDispatcher(App::makeRoutes());
        else
            App::$dispatcher = \FastRoute\cachedDispatcher(App::makeRoutes(), [
                'cacheFile' => dirname(dirname(dirname(__FILE__))) . "/cache/route.cache", 
            ]);

        pluginManager::hook('router-done');
        pluginManager::hook('twig-init');
        App::$twig_loader = new FilesystemLoader(dirname(dirname(dirname(__FILE__))) . "/views/");
        App::$twig = new Environment(App::$twig_loader, [
            'cache' => config::isCache() ? dirname(dirname(dirname(__FILE__))) . "/cache/" : false,
        ]);
        pluginManager::hook('twig-done');

    }

    private static function makeRoutes() {

        return function(\FastRoute\RouteCollector $r) {

            foreach (App::$routes as $route) {
                
                if(is_array($route['path']))
                    foreach ($route['path'] as $url)
                    {
                        if(!config::getCaseSensitive())
                            $url = strtolower($url);
                        $r->addRoute('GET', $url, $route);
                    }
                else
                    if(!config::getCaseSensitive())
                        $route['path'] = strtolower($route['path']);
                    $r->addRoute('GET', $route['path'], $route);    

            }

        };

    }

    public static function Run()
    {
        
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = (config::getCaseSensitive())? rawurldecode($uri) : strtolower(rawurldecode($uri));
        database::add([ "parm_2" => $uri]);
        $routeInfo = App::$dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                pluginManager::hook('404', $route);
                $handler = [
                    'view' => '404.html'   
                ];
                database::add([ "parm_1" => '404.html']);
                
                App::Render($handler);
            break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                
                if(!isset($handler['url'])) {
                    database::add([ "parm_1" => $handler['view']]);
                    
                    if (isset($vars))
                    {
                        for ($i = 2; $i < sizeof($vars); $i++) 
                            database::add([ "parm_". $i => $vars[$i]]);
                    }
            
                    if(isset($handler['databases']))
                        for ($i = 0; $i < sizeof($handler['databases']); $i++)
                            database::load($handler['databases'][$i]);
                                
                    App::Render($handler);                    
                
                }
                else {
                    App::_304($handler);
                }

                break;

            default:
                pluginManager::hook('500', $route);
                $handler = [
                    'view' => '500.html'   
                ];
                database::add([ "parm_1" => '500.html']);
                
                App::Render($handler);  
            break;
        }

    }

    private static function _304($route) {
        pluginManager::hook('304', $route);
        header('Location: ' . $route['url']);
    }

    private static function Render($route) {

        function loadtime() {
            global $start_time;
            $end_time = microtime(TRUE);
            $time_taken = $end_time - $start_time;
            return round($time_taken,5);
        }
    
        App::$twig->addGlobal('load_time', loadtime());

        pluginManager::hook('render');
        
        echo App::$twig->render($route['view'], database::$data);

    }

    public static function addGlobal($key, $value) {
        App::$twig->addGlobal($key, $value);
    }
    
    public static function addExtension($extension) {
        App::$twig->addExtension($extension);
    }

}
