<?php
namespace nqs;

use Symfony\Component\Yaml\Yaml;

class datafile {

    private static function get_extension($file) 
    {
        $extension = @end(explode(".", $file));
        return $extension ? $extension : false;
    }

    public static function read($path)
    {

        $data = [];

        $file_data = @file_get_contents( dirname(dirname(dirname(__FILE__))) . "/database//" . $path );

        if(isset($file_data)){
            switch(datafile::get_extension($path))
            {

                case "json":

                    $data = json_decode($file_data, true);

                break;

                case "yml":

                    $data = Yaml::parse($file_data);

                break;

                case "php":

                    $data = include_once $file_data;

                break;

            }

            return $data;

        }

        return false;

    }

    public static function write($path, $data)
    {

        $file_data = [];

        switch(datafile::get_extension($path))
        {

            case "json":

                $file_data = json_encode($data);

            break;

            case "yml":

                $file_data = Yaml::dump($data);

            break;

            case "php":

                $file_data = serialize($data);

            break;
        }
            
        file_put_contents(dirname(dirname(dirname(__FILE__))) . "/database//" . $path, $file_data);

    }

}
