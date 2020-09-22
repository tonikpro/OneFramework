<?php 
namespace etc\app;
use controller;

class App
{
    private $config;
    private $components;
    public $request;
    public $session;

    private static $instance;

    private function __construct()
    {
        $this->config = new Config();
        $this->request = new Request();
        $this->session = new Session();
    }

    private function load()
    {
        $components = $this->config->get('components');
        if(!is_null($components)){
            foreach($components as $key => $component)
            {
                if(array_key_exists('class', $component)){
                    if(!class_exists($component['class'])){
                        throw new \Exception(sprintf('Component <b>%s</b> not found', $component['class']));
                    }
                    $this->components[$key] = new $component['class']($component);
                }
            }
        }
    }

    public function config($name)
    {
        return $this->config->get($name);
    }

    public function __get($name) 
    {
        if(!array_key_exists($name, $this->components)){
            return null;
        }
        return $this->components[$name];
    }

    public static function i()
    {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function Run()
    {
        @set_exception_handler([$this, 'error_hundler']);
        $this->load();
        $path = $_SERVER['REQUEST_URI'];
        if($_SERVER['QUERY_STRING'] != ""){
            $path = substr($path, 0, strpos($path, "?"));
        }
        $path = trim($path, '/');
        $path = explode('/', $path);
        
        $params = [];
        if(count($path) < 2){
            $controller = 'Main';
            if($path[0] == ""){
                $action = 'actionMain';
            }else{
                $action = 'action' . $this->prepareObjectName(($path[0]));
            }
        }else{
            $controller = $this->prepareObjectName(($path[0]));
            $action = 'action' . $this->prepareObjectName(($path[1]));
            $params = array_slice($path, 2);
        }
        parse_str($_SERVER['QUERY_STRING'], $output);
        $params = array_merge($params, $output);
            
        $controller = '\\controller\\' . $controller;
        if(!class_exists($controller) || !array_key_exists('etc\app\Controller', class_parents($controller))) {
            return $this->response(404, "Not Found");
        }
        
        
        $c = new $controller();
        if(!method_exists($c, $action)){
            return $this->response(404, "Not Found");
        }
        return $c->$action($params);
    }

    public function error_hundler($e)
    {
        if($this->config('debug')){
            $view_path = __DIR__ . '/../../view/error.php';
            require $view_path;
        }
        error_log($e);
    }

    private function prepareObjectName($name)
    {
        $name = strtolower($name);
        if(strpos($name, '-') === false){
            return ucfirst($name);
        }
        $name = explode('-', $name);
        for($i = 0; $i < count($name); $i++)
        {
            $name[$i] = ucfirst($name[$i]);
        }
        $name = implode('', $name);
        return $name;
    }

    public function redirect($url, $code = 302)
    {
        $this->response($code, "Location: " . $url);
    }

    public function response($statusCode, $message, $body = "", $content_type = "text/html")
	{
		header($message, true, $statusCode);
		header("Content-Type: " . $content_type . "; charset=utf-8");
		if (!empty($body))
		{
			echo $body;
        }
        exit;
    } 
    
    public function json($content)
	{
		$this->response(200, "Ok", json_encode($content), "application/json");
	}
}