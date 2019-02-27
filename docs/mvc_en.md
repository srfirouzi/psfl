# MVC

simple mvc lib, :)

feature:
* simplex
* single file lib don't need dependency

---

# Model class

base class of model

model must:
* extend from Model class
* model class name must first character of name UpperCase and suffix by "Model" example : [book => Book Model]
* file name must Class name suffix by ".php" example: [book => BookModel.php] 
* model file must in model path

(only amke one instance of model on any request)

## peroperty

### app
this mvc parent instance object

```php
$model->app->view("page",['title'=>'hello']);
```
---

# Controller class

base class of Controller

Controller must: 
* extend from Controller class
* Controller class name must first character of name UpperCase and suffix by "Controller" example : [home => HomeController]
* file name must Class name suffix by ".php" example: [home => HomeController.php] 
* Controller file must in controller path

(maybe make many instance of Controller on any request)

## peroperty

### app
this mvc parent instance object

```php
$controller->app->view("page",['title'=>'hello']);
```
---

# view

all view is php file exist in view path

view must:
* view file is simplex php file suffix by ".php"
* name separate by '.' for directory separator
* can use global function

[ name = 'user.list' => file = /user/list.php]

---


# MVC
main class for mvc libreres, 

## MVC(model_path,view_path,controller_path,parent)

* model_path : path content model files
* view_path : path content view files
* controller_path : path content controller files
* parent :parent object for model and controller .if don't set ,use mvc object (usable for framework)

```php
$model_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'models';
$view_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views';
$controller_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'controllers';

$mvc=new MVC($model_path, $view_path, $controller_path);

```

## method

### model(name)
Returns the model with the specified name. You can use the __get magic method for the model, if used earlier than this model, does not generate a new instance of the model, returns the old instance of the model.

* name : model name

```php
$model=$mvc->model('phone'); // or $mvc->phone
```

### view(name,data=[])
render view and return resolt
* name : name of template
* data : associative array of the variable on template

```php
$data=[
	'items'=>[1,2,3,4]
];

$model=$mvc->view('list',$data); 
```

### controller(name)
Returns the controller with the specified name.Generates a new instance of the controller

```php
$model=$mvc->controller('home'); 
```
# example


main file:

```php
<?php
include 'MVC.php';

$model_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'models';
$view_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views';
$controller_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'controllers';

$mvc=new MVC($model_path, $view_path, $controller_path);

$home = $mvc->contrller('home');

echo $home->index("phone list");
?>
```

controller file

/controllers/HomeController.php

```php
<?php
class HomeController extends Controller{
    function index($title) {
        $model=$this->mvc->phone;// or $this->mvc->model('phone')
        $view_data=[
            'title'=>$title,
            'items'=>$model->get_list()
        ];
        return $this->mvc->view('list',$view_data);
    }
    
}
?>
```

model file

/models/PhoneModel.php

```php
<?php
class PhoneModel extends Model{
    function get_list() {
        return [
            ['name'=>'seyed rahim firouzi','phone'=>':)'],
            ['name'=>'seyed ali firouzi','phone'=>':o'],
            ['name'=>'seyed karim firouzi','phone'=>':|'],
            ['name'=>'seyed akbar firouzi','phone'=>':/'],
        ];
    }
}
?>
```

view file

/views/list.php

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

output

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









