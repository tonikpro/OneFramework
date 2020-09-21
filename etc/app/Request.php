<?php 
namespace etc\app;

class Request 
{
    public function get($name = null, $defaultValue = null)
    {
        if(is_null($name)){
            return $_GET;
        }
        return array_key_exists($name, $_GET) ? $_GET[$name] : $defaultValue;
    }

    public function post($name = null, $defaultValue = null)
    {
        if(is_null($name)){
            return $_POST;
        }
        return array_key_exists($name, $_POST) ? $_POST[$name] : $defaultValue;
    }
}