<?php 
namespace etc\components;

class UrlManager
{
    private $base_url;

    public function __construct($config)
    {
        if(isset($config['base_url'])){
            $this->base_url = rtrim($config['base_url'], '/');
        }
    }

    public function current($params = []) //[key => value]
    {
        $path = $_SERVER['REQUEST_URI'];
        if($_SERVER['QUERY_STRING'] != ""){
            $path = substr($path, 0, strpos($path, "?"));
        }
        $url = $this->base_url . '/' . trim($path, '/');
        if(is_array($params) && count($params) > 0){
            foreach($params as $key => $value)
            {
                $_GET[$key] = $value;
            }
            $url .= '?' . http_build_query($_GET);
        }
        return $url;
    }

    public function createAbsoluteUrl($path, $params = [])
    {
        $url = $this->base_url . '/' . trim($path, '/');
        if(is_array($params) && count($params) > 0){
            foreach($params as $key => $value)
            {
                $_GET[$key] = $value;
            }
            $url .= '?' . http_build_query($_GET);
        }
        return $url;
    }
}