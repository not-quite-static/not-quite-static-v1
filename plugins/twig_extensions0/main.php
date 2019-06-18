<?php

use nqs\datafile;
use nqs\pluginManager;

pluginManager::add_listener("init", "register_Twig_Extension");

function register_Twig_Extension()
{
    \nqs\render::addExtension(new twig_extension1_twig());
}

class twig_extension1_twig extends \Twig_Extension {

    public function getFunctions()
    {
        return array(
            new \Twig\TwigFunction('read_data', array($this, 'read_data')),
        );
    }

    public function read_data($path)
    {
        return datafile::read($path);
    }

}