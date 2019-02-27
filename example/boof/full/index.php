<?php

include '../../../Boof.php';

$viewPath= dirname ( __FILE__ ) .DIRECTORY_SEPARATOR.'views';

$boof=new Boof($viewPath);
$boof->add_function('external', function ($a,$b){ return $a.' like '.$b.' number '; } );

$data=[
    'str'=>'seyed rahim firouzi',
    'a'=>12 ,
    'list'=>['red','green','blue'],
    'ass'=>[
        'zero'=>':)',
        'one'=>'first',
        'two'=>'second'
    ],
    'my'=>'seyed rahim firouzi',
    
    
];



echo $boof->view('main',$data);



?>