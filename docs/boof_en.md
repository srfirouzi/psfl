# boof

boof is fast and simple template engine



# Introduction

Boof is a simple and fast template engine for use on the web.

## Properties

The template used in the Boof have the following properties

- Codec utf-8 without BOM
- File with html extension
- The template consists of two parts
     - tag
     - static part
- Tag components only with a ascii code
- Ability to use Unicode within the tag within the static string
- Use caching compile result to improve performance

# Boof class

## Boof(path,debug_mode=false)

* path : path of template engine(cache file in ".cache" in this directory)
* debug_mode : if this true then debug tag run

```php
$viewPath= dirname ( __FILE__ ).'/views' ;

$boof=new Boof($viewPath);
```
## method

### set_path(path)

change path of views file

* path : new path

```php
$viewPath= dirname ( __FILE__ ).'/views2' ;

$boof->set_path($viewPath);

```
### view(name,env=[]) 

render template file and return rendering text

* name : file name ,for directory seprator use "."
* env : associative array for data 

```php
$boof->view('users.list',['title'=>'user list','items'=>$lists]);

```

### render(src,env=[]) 

render template text and return rendering text

* src : template text
* env : associative array for data 


```php
$boof->render('<h1>{{title}}</h1>',['title'=>'user list']);
```

### add_function(name, func)

add external function in template engine

* name : name of function on template
* func : callable element for used to run ,return reader text

```php
$boof->add_function("hello",function($name){return "hello ,".$name; });
echo $boof->render('{{hello "seyed rahim" }}');

```
# template

## TAG

The tag is a piece of code that we have between the two symbols {{,}} and parts separated by space , The tag part run by template engine then return result

## static part

Each section of the template that is not a tag is a static part. static part appear without computation in the output.

example

```
    hello , <b> {{ your.name }} </b>
    {{format "%s-%s-%s" 2019 1 1 }}

```

if define

your.name = "Seyed Rahim Firouzi"

output

```
    hello , <b> Seyed Rahim Firouzi </b>
    2019-1-1
```

## Comment

To write the Comment within the tag it is enough to begin by // . The comment goes on until it reaches the end of the tag. It is also possible to write multi-line comment

example

```
{{var name = "Seyed Rahim" // this is comment}}
{{// this is comment}}
{{//this is 
multi line comment}}
```

## white space

If the tag is followed by the start with - and before the end with - the previous and the following space are deleted.

example

```
123
     {{- var a = "line2" -}}  4
line3
don't remove  {{ "|" -}}    remove space
remove space  {{- "|" }}    don't remove
```

output

```
1234
line3
don't remove  |remove space
remove space|    don't remove

```


## Variable

Values are specified by the variables to the template engine or at the template level. The variable name follows the standard C programming language and is used to access item objects from the point. For the item array the item is used for pointing. Also, the dynamic language variables have the ability to change the type automatically.

example

```
ali  
object.name
array.2
layout.content
red5
```

## Variable

Each programming language needs to define a variable, and thus defines the type of variable. The Boof Template language is also not excluded. Variants of variables are


### null

This type of variable in essence means no definition or non-existence, and in practice it does not have meaning. It becomes a string "".

example

```
{{var data = null }}
```

### bool

The boolean type is a variable with the correct or false value. And only these two values are taken

- true
- false

example

```
{{ var data = true}}
```

### number

Any integer or decimal number

sample

```
{{ var data = 12.34 }}
```

### string

A string that can be composed of control character and unicode control agents. To define the values of a string in "," . Controls character are:

- \n  new line
- \r  end line
- \\"  " character 
- \\\  \ character 
- \t  tab character

sample

```
{{ var data = "this is a book \n این یک کتاب هست" }}
```


### array

list of element define element inside of bracket and seprate by ","

sample

```
{{ var data=[1,2,3,4]}}
```

### object
list of key,value elements define element inside of { , } and seprate by "," and key and value seprate by ":" ,don't use " outside of key

sample

```
{{ var obj ={ name:"rahim", books:20  } }}
```

sample

```
{{ null }} 
{{ true }} 
{{ 12.34 }}
{{ "hellow \t world" }}
{{ [ false , 123 , "hello" ] }}
{{ {color : "blue" , age : 22}  }}
```





## falsy value

maybe used value in control flow object,must design way to convert to boolean type ,every value is true .only table value convert to false

falsy table

|  type |   value    |
|:-----:|:----------:|
|null   |null        |
|bool   |false       |
|number |0           |
|string |""          |
|array  |empty array |
|object |empty object|


## Control elements 

### if

for define condition in run flow ,
use two type 

- with opereator
- without opereator


```
{{ if ...}}
 part run if condition is true 
{{ else }}
 part run if condition is false 
{{end}}
```

can remove else parts

```
{{ if ...}}
 part run if condition is true 
{{end}}
```

#### with opereator

this mode use one of operator for check or condition

```

{{ if var1 operator var2}}
 part run if condition is true 
{{ else }}
 part run if condition is false 
{{end}}
```

operator list
 1. (==) check elements equal
 2. (!=) check elements don't equal
 3. (>) check elements greater
 4. (<) check elements smaller
 5. (>=) check elements greater or equal
 6. (<=) check elements smaller or equal
 7. (in) check first element inside second element
 
sample

```
{{ var garde = 20 }}
{{ if grade >= 3 }}
   this is best grade
{{else}}
    this is bad grade
{{end}}
```
output

```
   this is best grade
```

#### without operator

this mode only one value used by control flow,if is Truthy,run true elements.or run else part

```
{{ if var1 }}
 part run if condition is true 
{{ else }}
 part run if condition is false 
{{end}}

```

sample

```
{{var holiday = true}}
{{ if holiday }} chose best day,{{end}}
```
output

```
 chose best day,
```


### for 

used "for" for define loop in template engine,in loop define variable automatically for use better than loop.


```
{{ for var1 in array}}
  block run for element of array
{{else}}
if array is empty run this block
{{end}}
```
this variable define in loop block 

| variable  |             use           |
|:---------:|:-------------------------:|
|for.index  |number of repeat in loop   |
|for.key    |key on current value       |
|for.first  |in first time is true      |
|for.last   |in last time is true       |

** first elemnt index equal 0

```
{{ var list = [1,2,3,4] }}
{{ for ele in list }}
   {{ for.index + 1 }} - {{ ele }} <br/>
{{ end }}
```

output

```
   1 - 1 <br/>
   2 - 2 <br/>
   3 - 3 <br/>
   4 - 4 <br/>
```

### debug

very elements in debug block ,analize and return value only if template engine in debug mode


```
{{debug}}
    {{var a = 22}}
    hello world
    ...

{{end}
```

### macro

used to define a function within the template. this function is sandbox and completely independent on the variable outside the function.

```
{{macro macroname param1 param2 ... }}
   body of macro
{{end}}

```
- macroname macro name
- param1  parameter one
- param1 parameter two
- ...




for call

```
{{macroname param1 param2 ...}}
```
- macroname macro name
- param1  parameter one
- param1 parameter two
- ...


sample

```
{{macro call a b}}
   {{a}} call {{b}} for work <br/>
{{end}}

{{ say "seyed rahim" "ali"}}
{{ say "bob" "alice"}}

```
output

```

seyed rahim call ali for work <br/>
bob call alice for work <br/> 
```

## Items

### var

used for set value on variable

```
var variableName = data
```

- variableName variable Name
- data value for set in variable

don't return any things

in right side you can use:
1. static value
2. calculate operator
3. Bultin
4. user native function
5. macro


```
{{ var name = "salam"}}
{{ var age = 5 + 1 }}
{{ var day = ? ligth  true false }}
{{ var html = ! "<>&" }}
{{ var data = userFunc p1 p2 p3 }}
{{ var form = macroName p1 p2 p3 }}
```

### calculate operator

used for simple calculate , first operator is high priority

1. (+) equal mathematical +
2. (-) equal mathematical -
3. (/) equal mathematical /
4. (*) equal mathematical *
5. (%) equal mathematical remaining
6. (~) concat string mode

```
{{ 1 + 2 * 3}}
{{5 % 2}}
{{ "hello " ~ "world"}}
```

output 

```
9
1
hello world
```

### Bultin

function define in template engine ,list of bultin

1. ? 
2. !
3. enum
4. %
5. format
6. layout
7. import

for use from  function,builtin or macro you must type function name and separate parameter after name by space, for operator don't need space after operator


#### ?

for use fast way from if/else


```
{{ ? condtion truePart FalsePart }}
```

- condtion condtion
- truePart part return if condition is true 
- FalsePart part return if condition is true 


sample

```
{{ ?  true  "this is true"  "this is false"}}
```

output:

```
this is true
```


#### !

used for encode to html (for special character like <,>,& )

```
{{ ! data }}
```
- data data for decode to html

codic string print in output

#### enum 

used for convert number to case of value

```
enum <number> <case zero> <case one> <case two>

```

- number number for mapping
- caseZero if number is zero return this case
- caseOne if number is one return this case
- caseTwo if number is two return this case
- ...



#### %

used for encode to url (for special character like space )


```
{{ % data }}
```

- data data for decode to url

codic string print in output


#### format 

used for formated text,like c language but by different elements

list of elements
1. %s used paramter without any encode
2. %h used paramter with html encode
3. %u used paramter with url encode

```
format formatedString p1 p2 p3 ...
```

- formatedString format string 
- p1 parameter one
- p2 parameter two
- p3 parameter 3
- ...

sormated string print


example

```
{{ ? true "true" "false" }}
{{ ! "go->" }} 
{{ enum 2 zero first second }}
{{ % "a b"}}
{{ format "%s:%s:%s %h" 12 24 30  ">"}}
```

output

```
true
go-&gt;
second
a+b
12:24:30 &gt;
```

#### import

import other template file and run it

```
{{import "header"}}
```

1. for directory separator use .
2. don't need file extension 
3. import method run in runtime ,n't compile time

#### layout

used for set layout.layout is other page ,this page render than replace in layout page

1. this page set in "content" variable in layout page
2. other variable in layout page set in page by "layout." before name(sample : "title" -> in main page "layout.title")
3. if seted value in "layout.content" ,don't use page resoult for output
4. this command like set valve in "layout.layout" 


# sample

lay.html

```
<div>{{title}}</div>
<div>
{{content}}
</div>
```

header.html

```
<h1>header</h1>
```

main.html

```
{{layout "lay" //equal var layout.layout = "lay"}}
{{import "header"}}
{{var layout.title = "hello"}}
seyed rahim
```

 main page output

```
<div>hello</div>
<div>
<h1>header</h1>
seyed rahim
</div>
```

### native function 
The functions that are introduced from the outside of the template engine are the calling method, such as the macro

for call

```
{{fun p1 p2 ...}}
```

- fun  native functin name
- p1 parameter one
- p2 parameter two
- ...

### variable

If the name of a variable or a static value used in a tag, its values are printed in the output without any spaces.

sample

```
{{ "hello,world"}}
```

output

```
hello,world
```