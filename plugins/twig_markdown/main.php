<?php

use nqs\datafile;
use nqs\pluginManager;
use nqs\render;
use Twig\Extension\AbstractExtension;

pluginManager::add_listener("init", "register_Twig_Extension_Markdown");

function register_Twig_Extension_Markdown()
{
    \nqs\render::addExtension(new twig_Markdown_twig());
}

class twig_Markdown_twig extends AbstractExtension {

    public function getFunctions()
    {
        return array(
            new \Twig\TwigFunction('render_file', array($this, 'render_file'), array('is_safe' => array('html'))),
            new \Twig\TwigFunction('render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render_file($path)
    {
        return $this->render(file_get_contents( dirname(dirname(dirname(__FILE__))) . "/database" . $path));
    }   

    public function render($string)
    {
        $Parsedown = new Parsedown();

        return $Parsedown->text($string);
    }

}