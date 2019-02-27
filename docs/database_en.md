# DataBase

simple class for work by database ,base of PDO (for work must include php extention in php.ini)

feature:
* simplex
* anti sql injection by prepared sql command
* single file lib don't need dependency
* for simple work don't need know sql

## condtion 

most important part of sql query is "WHERE" part(condition),for is use simple ,  this librery use three way  

1. by id ,this way query or command execute on element by id(all method end by _id )
2. by parameter,this way make where string by associative array like the sample (all method end by _parameter)
    - example if parameter = [a=>b,c=>d],where = " a=b and c=d "
3. by where and params ,this way equal prepared command (if dont use prepared command ,maybe sql injection happen. if use prepared sql command parameters,sql injection never happen)


# DataBase class


## DataBase(dsn,user,pass,prefix)

* dsn : database dsn definection
* user : user of database
* pass : password of user
* prefix : string added in perfix of table name for advance method(on raw method must add by user)

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

## raw method

### get_table_name(table)

add prefix to name and return them,for use in raw method

* table : table name

```php
//$name="sfl_phone"
$name=$db->get_table_name("phone")
```
###  execute(sql,params=[],returnmode=DataBase::RETURN_SUCCESSFUL)

execute sql command and return value dependent return mode

* sql    : string of sql
* params : prepared sql command parameters
* returnmode :dependent on this value return data
     1. DataBase::RETURN_SUCCESSFUL : return boolean value,if execute sql SUCCESSFUL return true else return false
     2. DataBase::RETURN_AFFECTED : return count of affected record by this command
     3. DataBase::RETURN_INSERT_ID : return  inserted id

```php

//echo inserted elemet id
echo $db->execute("INSERT INTO sfl_phone (name, phone)VALUES (:n,:p)",['n'=>'rahim','p'=>':)'],DataBase::RETURN_INSERT_ID);

```

### query(sql,params=[])
return sql query in array of associative array from query

* sql    : string of sql
* params : prepared sql command parameters

```php
$data= $db->query("SELECT * FROM sfl_phone; ");

```
# advance method

for fast,safely,this method used


feature:
* condition technics
* associative array for data
* automatic add prefix to table name


### insert(table, data)

 insert data in table,return new record id
 
* table : name of table
* data  : associative array for define data

```php
$data=[
    'name'=>'seyed rahim firouzi',
    'phone'=>':)',
    'groups'=>'[frends][hacker]'
];
$id= $db->insert('phone',$data );
```


## delete(table, where = '',param=[], limit = -1)
delete element by where and prepared parameter , return count of affected record
* table : name of table
* where : where condition
* param : parameter from prepared command
* limit : by this parameter limit count of affected record

```php
echo $db->delete('phone','name=:name ', ['name'=>'seyed rahim firouzi'] );
```

## delete_id(table, id )
delete element by id , return true if deleted element
* table : name of table
* id : element id

```php
echo $db->delete('phone',12 );
```
## delete_parameter(table,param=[], limit = -1)
delete element by parameter , return count of affected record
* table : name of table
* param : parameter from make where string
* limit : by this parameter limit count of affected record

```php
echo $db->delete('phone', ['name'=>'seyed rahim firouzi'] );
```

## update(table, data, where = '',param=array())
update table data by where and prepared parameter , return count of affected record
* table : name of table
* data  : associative array for define data
* where : where condition
* param : parameter from prepared command

```php
echo $db->update('phone', ['phone'=>':o'], " phone = :pa ",['pa'=>':(']); 

```
## update_id(table, data, id)
update table data by id , return true if update element
* table : name of table
* data  : associative array for define data
* id : element id

```php
echo $db->update('phone', ['phone'=>':o'], " phone = :pa ",12);

```
## update_parameter(table, data,param=array())
update table data by parameter , return count of affected record
* table : name of table
* data  : associative array for define data
* param : parameter from make where string

```php
echo $db->update('phone', ['phone'=>':o'], ['name'=>'seyed rahim']);
```

## select(table,where='',param=[], offset = 0, limit = 0, by = '', order = 'ASC')
return array fo record dependent of other parameter by where and prepared parameter
* table : name of table
* where : where condition
* param : parameter from prepared command
* offset: pffset of record for return 
* limit : maximum count of record for return
* by    : sort element by field name,(can use array or string content field name separate  ',') 
* order : define order of sort can = ASC or DESC

```php
print_r($db->select('phone','',[],0,0,'name,phone','DES'));
```
## select_parameter(table,param=[], offset = 0, limit = 0, by = '', order = 'ASC')
return array fo record dependent of other parameter by parameter
* table : name of table
* param : parameter from make where string
* offset: pffset of record for return 
* limit : maximum count of record for return
* by    : sort element by field name,(can use array or string content field name separate  ',') 
* order : define order of sort can = ASC or DESC

```php
print_r($db->select_parameter('phone',['phone'=>'123'],0,0,'name,phone','DES'));
```

## get(table,where='',params=[], by = '', order = 'ASC'){
return first record dependent of other parameter(like select) by where and prepared parameter
* table : name of table
* where : where condition
* param : parameter from prepared command
* by    : sort element by field name,(can use array or string content field name separate  ',') 
* order : define order of sort can = ASC or DESC

```php
print_r($db->get('phone'));
```

## get_parameter(table,params=[], by = '', order = 'ASC'){
return first record dependent of other parameter(like select) by parameter
* table : name of table
* param : parameter from make where string
* by    : sort element by field name,(can use array or string content field name separate  ',') 
* order : define order of sort can = ASC or DESC

```php
print_r($db->get_parameter('phone',['name'=>'seyed rahim']));
```

## get_id(table,id){
return element by id
* table : name of table
* id : element id

```php
print_r($db->get_id('phone',12));
```

## count(table, where='',param=[])
return count of record dependent of other parameter(like select) by where and prepared parameter
* table : name of table
* where : where condition
* param : parameter from prepared command

```php
echo $db->count('phone');
```
## count_parameter(table, param=[])
return count of record dependent of other parameter(like select) by parameter
* table : name of table
* param : parameter from make where string

```php
echo $db->count('phone',['phone'=>'']);
```

## backup(tables = '*', fileName = '',compression=true) 
make backup from database in file,than return file name
* tables      : string of tables name separate  ',' or '*' for all table
* filename    : filename without extension
* compression : use gzip for compression



