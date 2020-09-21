<?php 
namespace etc\app;

class Controller 
{
    protected $request;
    protected $session;

    public function __construct()
    {
        $this->request = App::i()->request;
        $this->session = App::i()->session;
    }

    public function redirect($url, $code = 302)
    {
        return App::i()->redirect($url, $code);
    }
    
    public function json($content)
	{
		return App::i()->json($content);
    }
    
    protected function render($view, $args = [])
    {
        echo App::i()->poop->render(__DIR__ . '/../../view/', $view, $args);
    }

    protected function csrf($name)
    {
        $value = $this->request->post('csrf', $this->request->get('csrf'));
        if($value == null){
            return false;
        }

        if(isset($_SESSION['csrf'][$name]) && $_SESSION['csrf'][$name] == $value){
            unset($_SESSION['csrf'][$name]);
            return true;
        }
        return false;
        
    }
}