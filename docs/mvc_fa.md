<div dir="rtl">

# MVC

کتاب خانه ساده mvc

خواص :
* ساده
* تک فایل بدون هیچ پیش نیاز


---

# کلاس Model

کلاس مبنا همه مدل ها

مدل باید
* از کلاس Model ارث بری داشته باشد
* نام کلاس مدل برابر با نام مدل که حرف اول آن بزرگ و انتهای نام آن کلمه Model باشد.[book => BookModel]
* فایل مربوط به کلاس مدل برابر با نام کلاس مدل با پسوند php می باشد [book =>BookModel.php]
* فایل مدل باید در داریکتوری تعریف شده مدل قرار گزفته شده باشد

(فقط یک شی از کلاس مدل برای کلیه ارجاعیات تولید و استفاده می گردد)


## خواص

</div>

### app

<div dir="rtl">

شی مادر برای MVC

</div>

```php
$model->app->view("page",['title'=>'hello']);
```

---

<div dir="rtl">

# کلاس Controller

کلاس مبنا همه کنترلر ها

کنترلر باید
* از کلاس Controller ارث بری داشته باشد
* نام کلاس کنترلر برابر با نام کنترلر که حرف اول آن بزرگ و انتهای نام آن کلمه Controller باشد.[home => HomeController]
* فایل مربوط به کلاس کنترلر برابر با نام کلاس کنترلر با پسوند php می باشد [home =>HomeController.php]
* فایل کنترلر باید در داریکتوری تعریف شده کنترلر قرار گزفته شده باشد

(با هر اجرا از کلاس کنترلر یک شی تولید و استفاده می گردد)


## خواص

</div>

### app

<div dir="rtl">

شی مادر برای MVC

</div>

```php
$controller->app->view("page",['title'=>'hello']);
```

---

# view

<div dir="rtl">

تما فایل های ویو در مسیر تعریف شده ویو قرار دارند

ویو باید:
* فایل ویو یک فایل ساده با پسوند php است
* نام ویو با نقطه به عنوان جدا کننده دایرکتوری استفاده می شود
* در فایل ویو به تمام توابع گلوبال دسترسی وجود دارد

[ name = 'user.list' => file = /user/list.php]

---

# کلاس MVC
کلاس اصلی کتابخانه 

</div>

## MVC(model_path,view_path,controller_path,parent=null)

<div dir="rtl">

* model_path : مسیر فایل های مدل
* view_path : مسیر فایل های ویو
* controller_path : مسیر فایل های کنترلر
* parent : ابجکت مادر که به ابجکت های کنترلر و مدل ارجاع می شود .در صورت تعریف نکردن ابجکت کنونی mvc به آنها ارجاع می گردد(کاربردی برای فرمورک ها)

</div>

```php
$model_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'models';
$view_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views';
$controller_path=dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'controllers';

$mvc=new MVC($model_path, $view_path, $controller_path);
```
<div dir="rtl">

## متد

</div>

### model(name)

<div dir="rtl">

مدل با نام مشخص شده را بر می گرداند.  می تواند از متد جادویی __get برای مدل استفاده کنید، اگر قبل از آن از این مدل استفاده شود، نمونه جدیدی از مدل را تولید نمی کند، نمونه قدیمی از مدل را بر می گرداند

* name : نام مدل

</div>

```php
$model=$mvc->model('phone'); // or $mvc->phone
```

### view(name,data=[])

<div dir="rtl">

تمپلت را با اطلاعات داده شده رندر و نتیجه را برمی گرداند

* name : نام تمپلت
* data : ارایه انجمنی جهت استفاده در تمپلت

</div>

```php
$data=[
	'items'=>[1,2,3,4]
];

$model=$mvc->view('list',$data); 
```

### controller(name)

<div dir="rtl">

کنترلر با نام مشخص شده را بر می گرداند.نمونه جدیدی از کنترلر را تولید می کند

</div>

```php
$model=$mvc->controller('home'); 
```
<div dir="rtl">

# مثال


فایل اصلی:

</div>

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
<div dir="rtl">

فایل کنترلر

</div>

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
<div dir="rtl">

فایل مدل

</div>

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
<div dir="rtl">

فایل ویو

</div>

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
<div dir="rtl">

خروجی

</div>

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









