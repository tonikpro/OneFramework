<?php 
class Autoloader
{
    public static function registerLoader()
    {
        spl_autoload_register(['Autoloader', 'loader'], true, true);
    }

    public static function loader($name)
    {
        $file = __DIR__ . '/../../' . str_replace("\\", DIRECTORY_SEPARATOR, $name) . '.php';
        
        if(file_exists($file))
        {
            require_once $file;
        }
    }
}