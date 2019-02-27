<?php
class Model{
    protected $app=null;
    public function __construct($app) {
        $this->app=$app;
    }
}

class Controller{
    protected $app=null;
    public function __construct($app) {
        $this->app=$app;
    }
}



class MVC{
    private $model_path;
    private $view_path;
    private $controller_path;
    private $parent;
    
    private $models=[];
    
    function __construct($model_path,$view_path,$controller_path,$parent=null) {
        $this->model_path=$model_path;
        $this->view_path=$view_path;
        $this->controller_path=$controller_path;
        if(is_null($parent)){
            $this->parent=$this;
        }else{
            $this->parent=$parent;
        }
    }
    //model
    function model($key) {
        if(isset($this->models[$key]))
            return $this->models[$key];
        $name=ucfirst($key).'Model';
        $path=$this->model_path.DIRECTORY_SEPARATOR.$name.'.php';
        if(file_exists($path)){
            include_once $path;
            $obj=new $name($this->parent);
            $this->models[$key]=$obj;
            return $obj;
        }
        return null;
    }
    function __get($name){
        return $this->model($name);    
    }
    //view
    /**
     * run template base of php,return resoult
     * @param string $name name of file without ".php" for directory separate use '.'
     * @param array $data associative array of data for show
     * @return string execute data
     */
    public function view($name,$data=[]){
        $__page=$this->view_path.DIRECTORY_SEPARATOR.str_replace('.', DIRECTORY_SEPARATOR, $name).'.php';
        $__data=$data;
        ob_start();
        foreach ($__data as $__k => $__v) {
            $$__k=$__v;
        }
        if(file_exists($__page))
            include($__page);
        $__out=ob_get_contents();
        ob_end_clean();
        return $__out;
    }
    //controller
    function controller($keu) {
        $name=ucfirst($key).'Controller';
        $path=$this->controller_path.DIRECTORY_SEPARATOR.$name.'.php';
        if(file_exists($path)){
            include_once $path;
            $obj=new $name($this->parent);
            return $obj;
        }
        return null;
    }
}

?>