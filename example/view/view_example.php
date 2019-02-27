<?php 

include '../../View.php';

$view=new View(dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views');//use /views for template path

$data=[
    'title'=>'phone list',
    'items'=>[
        ['name'=>'seyed rahim firouzi','phone'=>':)'],
        ['name'=>'seyed ali firouzi','phone'=>':o'],
        ['name'=>'seyed karim firouzi','phone'=>':|'],
        ['name'=>'seyed akbar firouzi','phone'=>':/'],
    ]
];



echo $view->view('test1',$data);

?>