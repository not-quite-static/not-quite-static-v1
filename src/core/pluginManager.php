<?php
namespace nqs;

class pluginManager {

    private static $listeners = array();

    public static function load() {

        $plugins = glob(dirname(dirname(dirname(__FILE__))) . '/plugins/*' , GLOB_ONLYDIR);
        foreach ($plugins as $value) {
            if(file_exists($value . "/main.php"))
                include $value . "/main.php";
        }

    }

    public static function hook() {
        global $listeners;

        $num_args = func_num_args();
        $args = func_get_args();

        if($num_args < 1)
            trigger_error("Insufficient arguments", E_USER_ERROR);

        $hook_name = array_shift($args);

        if(!isset($listeners[$hook_name]))
            return;

        foreach($listeners[$hook_name] as $func) {
            $args = $func($args); 
        }
        return $args;
    }

    public static function add_listener($hook, $function_name) {
        global $listeners;
        $listeners[$hook][] = $function_name;
    }

}