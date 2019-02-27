<?php 
include '../../MVC.php';

$model_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'models';
$view_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views';
$controller_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'controllers';

$mvc=new MVC($model_path, $view_path, $controller_path);

$home = $mvc->controller('home');

echo $home->index("phone list");


?>