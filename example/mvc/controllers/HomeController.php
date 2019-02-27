<?php 
class HomeController extends Controller{
    function index($title) {
        $model=$this->app->phone;// or $this->mvc->model('phone')
        $view_data=[
            'title'=>$title,
            'items'=>$model->get_list()
        ];
        return $this->app->view('list',$view_data);
    }
    
}




?>