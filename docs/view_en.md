# view

ultra fast template engine class, base of php output buffer,

feature:
* simplex
* useable for microframework
* single file lib don't need dependency
* for simple work don't need learn new template engine language

## View(path)

* path : path of template exist in them 

```php
// path is ./views
$engine=new View( dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views' );

```

## template

all template is php file ,on template path,name is without ".php" and use "." for directory seprator,on template variable equal variable sent to view method associative array

# method

## view(name,data=[])
render template and return result
* name : name of template engine
* data : associative array of the variable on template

```php

// path is ./views
$engine=new View( dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views' );
$data=[
	 'title'=>'phone list',
    'items'=>[
       ['name'=>'seyed rahim firouzi','phone'=>':)'], 
       ['name'=>'seyed ali firouzi','phone'=>':o'], 
       ['name'=>'seyed karim firouzi','phone'=>':|'],
       ['name'=>'seyed akbar firouzi','phone'=>':/'],
    ] 
];



echo $view->view('list',$data);

```
./view/list.php

```php
<?php echo $title ;?>
<table style="width:100%">
  <tr>
    <th>name</th>
    <th>phone</th>
  </tr>
<?php for($i=0;$i<count($items);$i++){ ?>
  <tr>
    <th><?php echo $items[$i]['name']; ?></th>
    <th><?php echo $items[$i]['phone']; ?></th>
  </tr>
<?php } ?>
</table>

```

output:

```html
phone list<table style="width:100%">
  <tr>
    <th>name</th>
    <th>phone</th>
  </tr>
  <tr>
    <th>seyed rahim firouzi</th>
    <th>:)</th>
  </tr>
  <tr>
    <th>seyed ali firouzi</th>
    <th>:o</th>
  </tr>
  <tr>
    <th>seyed karim firouzi</th>
    <th>:|</th>
  </tr>
  <tr>
    <th>seyed akbar firouzi</th>
    <th>:/</th>
  </tr>
</table>
```



