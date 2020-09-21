<?php 
namespace etc\app;

class Model
{
    protected $errors = [];
    protected $data = [];

    public function __construct($data = [])
    {
        $name = $this->get_class_name();
        if(is_array($data) && isset($data[$name]) && is_array($data[$name])){
            foreach($data[$name] as $key => $value){
                $this->$key = $value;
            }
            // $this->data = $data[$name];
        }
    }

    private function get_class_name()
    {
        $classname = get_class($this);
        if ($pos = strrpos($classname, '\\')) {
            return substr($classname, $pos + 1);
        }
        return $classname;
    }

    public function errors($name = '')
    {
        if($name == ''){
            return $this->errors;
        }else{
            if(isset($this->errors[$name])){
                return $this->errors[$name];
            }
        }
        return null;
    }

    public function __get($name)
    {
        if(!isset($this->data[$name])){
            return null;
        }
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        if(is_string($value)){
            $value = trim($value);
        }
        $this->data[$name] = $value;
    }

    public function value($value, $default = null)
    {
        if(is_null($value)){
            return $default;
        }
        return $value;
    }
}