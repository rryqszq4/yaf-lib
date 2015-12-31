php-cjson
===========
[![Build Status](https://travis-ci.org/rryqszq4/php-cjson.svg?branch=master)](https://travis-ci.org/rryqszq4/php-cjson)

The php-cjson is a fast JSON parsing and encoding support for PHP extension.


Install
-------
```
$/path/to/phpize
$./configure --with-php-config=/path/to/php-config
$make && make install
```

Example
-------
**encode**
```php
<?php
$arr = array(
	1,
	"string",
	array("key"=>"value")
);
var_dump(cjson_encode($arr));

/* ==>output
string(28) "[1,"string",{"key":"value"}]";
*/
?>
```

**decode**
```php
<?php
$str = '[1,"string",{"key":"value"}]';
var_dump(cjson_decode($str));

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
?>
```

