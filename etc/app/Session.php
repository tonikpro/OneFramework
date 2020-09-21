<?php 
namespace etc\app;

class Session 
{
    public function get($name, $defaultValue = null)
    {
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : $defaultValue;
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
}