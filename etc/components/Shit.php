<?php 
namespace etc\components;
use etc\app\App;

class Shit
{
    public function __construct($config){}

    public function render($root, $view, $args = [])
    {
        $view = rtrim($root, '/') . '/' . $view . '.php';
        if(!file_exists($view)){
            throw new \Exception(printf('View <b>%s</b> not found', $view));
        }
        if(is_array($args)){
            foreach($args as $key => $value)
            {
                $$key = $value;
            }
        }
        ob_start();
        require $view;
        $content = ob_get_contents();
        ob_clean();
        if(isset($layout)){
            $layout = rtrim($root, '/') . '/layout/' . $layout . '.php';
            if(file_exists($layout)){
                require $layout;
                $content = ob_get_contents();
            }
        }
        ob_end_clean();
        return $content;
    }

    private function csrf($name)
    {
        $value = md5(microtime());
        $_SESSION['csrf'][$name] = $value;
        return '<input type=\'hidden\' name=\'csrf\' value=\''.$value.'\' \>';
    }

    // private function form($config)
    // {
    //     /*
    //     config
    //     [
    //         attr = [method, action, enctype, ...],

    //     ]
    //     */
    // }

    // private function beginForm($config = [])
    // {
    //     $form = '<form';
    //     if(is_array($config) && count($config) > 0){
    //         foreach($config as $key => $value)
    //         {
    //             $form .= ' ' . $key . '="'.$value.'"';
    //         }
    //     }
    //     $form .= '>';
    //     return $form;
    // }

    // private function endForm()
    // {
    //     return '</form>';
    // }
}