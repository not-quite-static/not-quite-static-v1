<?php

use nqs\datafile;
use nqs\pluginManager;
use Twig\Extension\AbstractExtension;

pluginManager::add_listener("twig-done", "register_Twig_Extension");

function register_Twig_Extension()
{
    \nqs\App::addExtension(new twig_extension1_twig());
    //\nqs\App::addGlobal("isTOR", isTOR());
    \nqs\App::addGlobal("POST", $_POST);
    \nqs\App::addGlobal("GET", $_GET);
}

function ReverseIPOctets($inputip){
    if($inputip == null)
        $inputip = "127.0.0.1";
    $ipoc = explode(".",$inputip);
    return $ipoc[3].".".$ipoc[2].".".$ipoc[1].".".$ipoc[0];
}

function isTOR()
{

    try {
        if (@gethostbyname(ReverseIPOctets($_SERVER['REMOTE_ADDR']).".".$_SERVER['SERVER_PORT'].".".ReverseIPOctets($_SERVER['SERVER_ADDR']).".ip-port.exitlist.torproject.org")=="127.0.0.2") {
            return true;
        }

        if (strpos($_SERVER['REQUEST_URI'], '.onion') !== false){
            return true;
        }

    } catch (\Throwable $th) {
        
    }

    return false;
}

class twig_extension1_twig extends AbstractExtension {

    public function getFunctions()
    {
        return array(
            new \Twig\TwigFunction('read_data', array($this, 'read_data')),
            new \Twig\TwigFunction('write_data', array($this, 'write_data')),
            new \Twig\TwigFunction('date', array($this, 'date')),
            new \Twig\TwigFunction('download_cahe', array($this, 'download_cahe')),
        );
    }

    public function date($format)
    {
        return date($format);
    }   

    public function write_data($path, $data)
    {
        return datafile::write($path, $data);
    }

    public function read_data($path)
    {
        return datafile::read($path);
    }
    
    public function download_cahe($url, $name, $ttl)
    {
        $cache_file =  dirname(dirname(dirname(__FILE__))) . "/cache/dl_" . $name;
        if (file_exists($cache_file) && (filemtime($cache_file) > (time() - $ttl ))) {
           $file = file_get_contents($cache_file);
        
        } else {

           $file = file_get_contents($url);
           file_put_contents($cache_file, $file, LOCK_EX);
        }
    }

}
