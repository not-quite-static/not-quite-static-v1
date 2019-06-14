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
            'cache' => $settings['render']['cache'] ? dirname(dirname(dirname(__FILE__))) . "/cache/" : false,
        ]);

    }

    public static function render($path, $data)
    {

        return render::$twig->render($path, $data);

    }


}