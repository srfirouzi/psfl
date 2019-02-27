<div dir="rtl">

# DataBase

کلاس ساده برای کار با دیتابیس .بر پایه PDO می باشد.می بایست در تنظیمات این نکته مد نظر گرفته شود


خواص:
* ساده
* بر پایه دستورات آماده جهت جلوگیری ازsql injection
* تک فایل و بدون هیچ پیش نیازی
* برای استفاده ساده و عدم نیاز به دانستن sql


## شرط

قسمت مهم در کوری sql مربوط به پارامتر "WHERE" می باشد.این کتابخانه از سه روش استفاده می کند

1. به وسیله id , در این روش از id جهت دسترسی استفاده می گردد (تمام متد های که انتهای نام آنها _id می باشد)
2. به وسیله خواص , در این روش با توجه به ارایه انجمنی ارجاع شده یک شرط به فرم مثال تولید می گردد(تمام متد های که انتهای نام آنها  _parameter می باشد) 
    - مثال اگر parameter = [a=>b,c=>d],آنگاه where = " a=b and c=d " 

3. به وسیله متن شرط و پارامتر های دستورات prepared( در صورت استفاده نکردن از prepared , امکان sql injection  وجود دارد ,در صورت استفاده از prepared هیچگاه sql injection اتفاق نمی افتد )



# کلاس DataBase 

</div>

## DataBase(dsn,user,pass,prefix)

<div dir="rtl">

* dsn :  dsn دیتا بیس 
* user : کاربر دیتا بیس
* pass : پسورد کاربر
* prefix : نام پیشوند تیبل های دیتا بیس (در متد های raw می بایست توسط کاربر اضافه گردد)

</div>

```php
/*

database name =psfl
database user =root
database pass =""
database table prefix= sfl_

CREATE TABLE `sfl_phone` (
 `id` INT NOT NULL AUTO_INCREMENT ,
 `name` TEXT NOT NULL , 
 `phone` TEXT NOT NULL , 
 `groups` TEXT NOT NULL , 
 PRIMARY KEY (`id`)
) ENGINE = InnoDB; 



 */

$db=new DataBase('mysql:host=127.0.0.1;dbname=psfl', 'root','','sfl_');

$data=$db->select('phone');

```
<div dir="rtl">

## متدهای raw 

</div>

### get_table_name(table)

<div dir="rtl">

پیشوند تیبل را به نام ارجاع داده می چسباند و نتیجه را برمی گرداند.کاربرد در متدهای raw

* table : نام تیبل

</div>

```php
//$name="sfl_phone"
$name=$db->get_table_name("phone")
```


###  execute(sql,params=[],returnmode=DataBase::RETURN_SUCCESSFUL)

<div dir="rtl">

sql را اجراع کرده و بر حسب پارامتر returnmode مقداری را برمی گرداند

* sql    : رشته sql
* params : پرامتر های  prepared sql command
* returnmode :وابسته مقدار این متغیر مقداری برگردانده می شود
     1. DataBase::RETURN_SUCCESSFUL : اگر دستور اجرا گردید مقدار true برمی گرداند
     2. DataBase::RETURN_AFFECTED : تعداد عنصر تاثیر گرفته از این دستور را برمی گرداند
     3. DataBase::RETURN_INSERT_ID : id آخرین insert را برمی گرداند

</div>

```php

//echo inserted elemet id
echo $db->execute("INSERT INTO sfl_phone (name, phone)VALUES (:n,:p)",['n'=>'rahim','p'=>':)'],DataBase::RETURN_INSERT_ID);

```

### query(sql,params=[])

<div dir="rtl">

نتیجه کوری را در یک ارایه از ارایه های انجمنی برمی گرداند


* sql    : دستور sql
* params : پرامتر های  prepared sql command

</div>

```php
$data= $db->query("SELECT * FROM sfl_phone; ");

```
<div dir="rtl">

# متد های پیشرفته

برای کاربرد سریع و امن از این متد ها استفاده می شود

خواص:
* تکنولوژی شرطی سه گانه
* استفاده از ارایه های انجمنی
* اضافه کردن اتوماتیک پیشوند

</div>

### insert(table, data)

<div dir="rtl">

قرار دادن اطلاعات در دیتا بیس و برگشت id اطلاعات جدید
 
* table : نام تیبل
* data  : آرایه انجمنی برای تعریف اطلاعات

</div>

```php
$data=[
    'name'=>'seyed rahim firouzi',
    'phone'=>':)',
    'groups'=>'[frends][hacker]'
];
$id= $db->insert('phone',$data );
```


## delete(table, where = '',param=[], limit = -1)

<div dir="rtl">

حذف اطلاعات با متن شرط و پارامتر های دستورات prepared , برگشت تعداد عنصر تاثیر گرفته از این دستور 

* table : نام تیبل
* where : متن شرط
* param : پرامتر های  prepared sql command
* limit : با این پارامتر تعداد عنصر که باید تاثیر بگیرند مشخص می شود 

</div>

```php
echo $db->delete('phone','name=:name ', ['name'=>'seyed rahim firouzi'] );
```

## delete_id(table, id )

<div dir="rtl">

حذف اطلاعات با id ,در صورت اجرای دستور مقدار true برمی گرداند

* table : نام تیبل
* id : id عنصر

</div>

```php
echo $db->delete('phone',12 );
```
## delete_parameter(table,param=[], limit = -1)

<div dir="rtl">

حذف اطلاعات به وسیله خواص , برگشت تعداد عنصر تاثیر گرفته از این دستور 
* table : نام تیبل
* param : خواص جهت تولید شرط از نوع ارایه انجمنی 
* limit : با این پارامتر تعداد عنصر که باید تاثیر بگیرند مشخص می شود 

</div>

```php
echo $db->delete('phone', ['name'=>'seyed rahim firouzi'] );
```

## update(table, data, where = '',param=array())

<div dir="rtl">

تغییر اطلاعات با متن شرط و پارامتر های دستورات prepared , برگشت تعداد عنصر تاثیر گرفته از این دستور 
* table : نام تیبل
* data  : آرایه انجمنی برای تعریف اطلاعات
* where : متن شرط
* param : پرامتر های  prepared sql command

</div>

```php
echo $db->update('phone', ['phone'=>':o'], " phone = :pa ",['pa'=>':(']); 

```
## update_id(table, data, id)

<div dir="rtl">

تغییر اطلاعات با id ,در صورت اجرای دستور مقدار true برمی گرداند
* table : نام تیبل
* data  : آرایه انجمنی برای تعریف اطلاعات
* id : id عنصر

</div>

```php
echo $db->update('phone', ['phone'=>':o'], " phone = :pa ",12);

```
## update_parameter(table, data,param=array())

<div dir="rtl">

تغییر اطلاعات به وسیله خواص , برگشت تعداد عنصر تاثیر گرفته از این دستور 
* table : نام تیبل
* data  : آرایه انجمنی برای تعریف اطلاعات
* param : خواص جهت تولید شرط از نوع ارایه انجمنی 

</div>

```php
echo $db->update('phone', ['phone'=>':o'], ['name'=>'seyed rahim']);
```

## select(table,where='',param=[], offset = 0, limit = 0, by = '', order = 'ASC')

<div dir="rtl">

برگشت لیست اطلاعات با متن شرط و پارامتر های دستورات prepared 
* table : نام تیبل
* where : متن شرط
* param : پرامتر های  prepared sql command* offset: مکان شروع از آیتم   
* limit : حداکثر تعداد برگشتی
* by    : مرتب سازی عناصر بر حسب نام فیلد(می شود از آرایه یا رشته که نام فیلد ها به وسیله "," از هم جدا شده باشند استفاده کرد) 
* order : نوع مرتب سازی  = ASC یا DESC

</div>

```php
print_r($db->select('phone','',[],0,0,'name,phone','DES'));
```
## select_parameter(table,param=[], offset = 0, limit = 0, by = '', order = 'ASC')

<div dir="rtl">

برگشت لیست اطلاعات به وسیله خواص  
* table : نام تیبل
* param : خواص جهت تولید شرط از نوع ارایه انجمنی 
* offset: مکان شروع از آیتم   
* limit : حداکثر تعداد برگشتی
* by    : مرتب سازی عناصر بر حسب نام فیلد(می شود از آرایه یا رشته که نام فیلد ها به وسیله "," از هم جدا شده باشند استفاده کرد) 
* order : نوع مرتب سازی  = ASC یا DESC

</div>

```php
print_r($db->select_parameter('phone',['phone'=>'123'],0,0,'name,phone','DES'));
```

## get(table,where='',params=[], by = '', order = 'ASC')

<div dir="rtl">

همانند متد select می باشد با این تفاوت که فقط عنصر اول را بر می گرداند
* table : نام تیبل
* where : متن شرط
* param : پرامتر های  prepared sql command* offset: مکان شروع از آیتم   
* by    : مرتب سازی عناصر بر حسب نام فیلد(می شود از آرایه یا رشته که نام فیلد ها به وسیله "," از هم جدا شده باشند استفاده کرد) 
* order : نوع مرتب سازی  = ASC یا DESC

</div>

```php
print_r($db->get('phone'));
```

## get_parameter(table,params=[], by = '', order = 'ASC')

<div dir="rtl">

همانند متد select_parameter می باشد با این تفاوت که فقط عنصر اول را بر می گرداند
* table : نام تیبل
* param : خواص جهت تولید شرط از نوع ارایه انجمنی 
* offset: مکان شروع از آیتم   
* by    : مرتب سازی عناصر بر حسب نام فیلد(می شود از آرایه یا رشته که نام فیلد ها به وسیله "," از هم جدا شده باشند استفاده کرد) 
* order : نوع مرتب سازی  = ASC یا DESC

</div>

```php
print_r($db->get_parameter('phone',['name'=>'seyed rahim']));
```

## get_id(table,id)

<div dir="rtl">

آیتم با id مشخص شده را برمی گرداند
* table : نام تیبل
* id : id عنصر

</div>

```php
print_r($db->get_id('phone',12));
```

## count(table, where='',param=[])

<div dir="rtl">

همانند متد select می باشد با این تفاوت که تعداد عناصر را بر می گرداند
* table : نام تیبل
* where : متن شرط
* param : پرامتر های  prepared sql command* offset: مکان شروع از آیتم   

</div>

```php
echo $db->count('phone');
```
## count_parameter(table, param=[])

<div dir="rtl">

همانند متد select_parameter می باشد با این تفاوت که تعداد عناصر را بر می گرداند
* table : نام تیبل
* param : خواص جهت تولید شرط از نوع ارایه انجمنی 

</div>

```php
echo $db->count('phone',['phone'=>'']);
```

## backup(tables = '*', fileName = '',compression=true) 

<div dir="rtl">

جهت بک آپ گیری از دیتا بیس نام فایل بک آپ گرفته را برمی گرداند
* tables      : رشته از نام تیبل ها و جدا شده با "," یا "*" برای همه تیبل ها 
* filename    : نام فایل بدون پسوند
* compression : از فشرده ساز استفاده نماید یا نه

</div>


