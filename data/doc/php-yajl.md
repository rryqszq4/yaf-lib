php-yajl
========
[![Build Status](https://travis-ci.org/rryqszq4/php-yajl.svg?branch=master)](https://travis-ci.org/rryqszq4/php-yajl)

The php-yajl is a simple fast JSON parsing and generation library YAJL(Yet Another JSON Library), Bindings for php extension.

You can read more info at the project's website [http://lloyd.github.com/yajl](http://lloyd.github.com/yajl)


Install
-------
```
$/path/to/phpize
$./configure --with-php-config=/path/to/php-config
$make && make install
```

Example
-------
**generation**
```php

$arr = array(
	1,
	"string",
	array("key"=>"value")
);
var_dump(yajl_generate($arr));

/* ==>output
string(28) "[1,"string",{"key":"value"}]";
*/

var_dump(yajl::generate($arr));

/* ==>output
string(28) "[1,"string",{"key":"value"}]";
*/

```

**parsing**
```php
$str = '[1,"string",{"key":"value"}]';
var_dump(yajl_parse($str));

/* ==>output
array(3) {
  [0]=>
  int(1)
  [1]=>
  string(6) "string"
  [2]=>
  array(1) {
    ["key"]=>
    string(5) "value"
  }
}
*/

var_dump(yajl::parse($str));

/* ==>output
array(3) {
  [0]=>
  int(1)
  [1]=>
  string(6) "string"
  [2]=>
  array(1) {
    ["key"]=>
    string(5) "value"
  }
}
*/
```