<?php
namespace nqs;

use \Twig\Loader\FilesystemLoader;
use \Twig\Environment;

class render {

    /*
    * $loader
    * @var \Twig\Loader\FilesystemLoader
    */
    private static $loader;

    /* 
    * $twig
    * @var \Twig\Environment
    */
    private static $twig;

    public static function init($settings)
    {

        render::$loader = new FilesystemLoader(dirname(dirname(dirname(__FILE__))) . "/views/");
        render::$twig = new Environment(render::$loader, [
            'cache' => $settings['cache'] ? dirname(dirname(dirname(__FILE__))) . "/cache/" : false,
        ]);

        if(isset($settings['twig_extension']))
            foreach ($settings['twig_extensions'] as $value) 
                render::addExtension($value);


    }

    public static function getTwigEnvironment()
    {
        return render::$twig;        
    }

    public static function addExtension($extension)
    {

        return render::$twig->addExtension($extension);

    }

    public static function render($path, $data)
    {

        return render::$twig->render($path, $data);

    }


}