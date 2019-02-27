<?php
class View{
    private $path ;//= dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views';
    /**
     * 
     * @param string $path path of view page
     */
    public function __construct($path=null){
        if(is_null($path)){
            $this->path= dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views';
        }else{
            $this->path=$path;
        }
    }
    /**
     * run template base of php,return resoult
     * @param string $name name of file without ".php" for directory separate use '.'
     * @param array $data associative array of data for show
     * @return string execute data
     */
    public function view($name,$data=[]){
        $__page=$this->path.DIRECTORY_SEPARATOR.str_replace('.', DIRECTORY_SEPARATOR, $name).'.php';
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
    
}

?>