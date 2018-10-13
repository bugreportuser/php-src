--TEST--
Test array_every() function
--FILE--
<?php
/*
	Prototype: bool array_every(array $array, mixed $callback);
	Description: Iterate array and stop based on return value of callback
*/

function is_int_ex($item)
{
	return is_int($item);
}

echo "\n*** Testing not enough or wrong arguments ***\n";

var_dump(array_every());
var_dump(array_every(true));
var_dump(array_every([]));
var_dump(array_every(true, function () {}));
var_dump(array_every([], true));

echo "\n*** Testing basic functionality ***\n";

var_dump(array_every([1, 2, 3], 'is_int_ex'));
var_dump(array_every(['hello', 1, 2, 3], 'is_int_ex'));
$iterations = 0;
var_dump(array_every(['hello', 1, 2, 3], function($item) use (&$iterations) {
	++$iterations;
	return is_int($item);
}));
var_dump($iterations);

echo "\n*** Testing second argument to predicate ***\n";

var_dump(array_every([1, 2, 3], function($item, $key) {
	var_dump($key);
	return true;
}));

echo "\n*** Testing edge cases ***\n";

var_dump(array_every([], 'is_int_ex'));

echo "\nDone";
?>
--EXPECTF--
*** Testing not enough or wrong arguments ***

Warning: array_every() expects exactly 2 parameters, 0 given in %s on line %d
NULL

Warning: array_every() expects exactly 2 parameters, 1 given in %s on line %d
NULL

Warning: array_every() expects exactly 2 parameters, 1 given in %s on line %d
NULL

Warning: array_every() expects parameter 1 to be array, bool given in %s on line %d
NULL

Warning: array_every() expects parameter 2 to be a valid callback, no array or string given in %s on line %d
NULL

*** Testing basic functionality ***
bool(true)
bool(false)
bool(false)
int(1)

*** Testing second argument to predicate ***
int(0)
int(1)
int(2)
bool(true)

*** Testing edge cases ***
bool(true)

Done
