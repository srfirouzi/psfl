<?php 
include '../../DataBase.php';
/*
database name =psfl
database user =root
database pass =""

CREATE TABLE `sfl_phone` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `name` TEXT NOT NULL , 
 `phone` TEXT NOT NULL , 
 `groups` TEXT NOT NULL , 
 PRIMARY KEY (`id`)
) ENGINE = InnoDB; 
 */

$db=new DataBase('mysql:host=127.0.0.1;dbname=psfl', 'root','','sfl_');

$db->delete('phone');//delete all data in table

echo "<h1>insert</h1>";
$data=[
    'name'=>'seyed rahim firouzi',
    'phone'=>':)',
    'groups'=>'[frends][hacker]'
];
$id= $db->insert('phone',$data );
echo "insert id = " . $id ;
$db->insert('phone',['phone'=>'123'] );
$db->insert('phone',['name'=>'me'] );
$db->insert('phone',['groups'=>'[hacker]'] );




echo "<br/>";

echo "<h1>update</h1>";

echo " update id phone to ':|' <br/>";
echo "update effected elements = ";
echo $db->update_id('phone', ['phone'=>':|'],$id); //where is number,where equal "id = $id" ($id is value of number) 
echo "<br/>";

echo " update id phone to ':(' <br/>";
echo "update effected elements = ";
echo $db->update_parameter('phone', ['phone'=>':('],['phone'=>':|']); //where is array,where equal ' phone = ":)" '
echo "<br/>";

echo " update id phone to ':o' <br/>";
echo "update effected elements = ";
echo $db->update('phone', ['phone'=>':o'], " phone = :pa ",['pa'=>':(']); //where is string content param and use param 
echo "<br/>";


echo " update id phone to ':)' <br/>";
echo "update effected elements = ";
echo $db->update('phone', ['phone'=>':)'], " phone = \":o\"" ); //where is default where string "raw mode"
echo "<br/>";

echo "<h1>delete</h1>";

echo $db->delete_parameter('phone',['groups'=>'[hacker]'] ); //where equal " groups = '[kacker]' "
echo "<h1>select/get/count</h1>";
echo "<pre>";
print_r($db->select('phone'));
echo "</pre>";
echo "<br/>";
echo "<pre>";
print_r($db->get('phone'));
echo "</pre>";
echo "<br/>";
echo $db->count('phone');




?>