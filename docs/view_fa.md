<div dir="rtl">

# view

تمپلت انجین فوق سریع بر مبنای output buffer

خواص
* ساده و سریع
* قابل استفاده در میکرو فرمورک ها
* کتابخانه تک فایل
* برای کار های ساده نیاز به یاد گیری زبان جدید تمپلت نیست

</div>

## View(path)

<div dir="rtl">

* path : مسیر فایل های تمپلت

</div>

```php
// path is ./views
$engine=new View( dirname( __FILE__ ) . DIRECTORY_SEPARATOR.'views' );

```
<div dir="rtl">

## تمپلت

تمام تمپلت ها فایل های php هستند.نام فایل تمپلت نام بدون پسوند فایل و از نقطه به عنوان جداکنده دایرکتوری استفاده شده است.تمام متغیر ها در تمپلت بابر با مقدایر آرایه انجمنی ارسالی به متد view می باشد.به توابع گلوبال دسترسی دارد.


# متد

</div>

## view(name,data=[])

<div dir="rtl">

تمپلت را با اطلاعات داده شده رندر و نتیجه را برمی گرداند

* name : نام تمپلت
* data : ارایه انجمنی جهت استفاده در تمپلت

</div>

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
<div dir="rtl">
خروجی:
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



