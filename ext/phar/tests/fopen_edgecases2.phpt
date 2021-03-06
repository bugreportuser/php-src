--TEST--
Phar: test edge cases of fopen() function interception #2
--SKIPIF--
<?php if (!extension_loaded("phar")) die("skip"); ?>
--INI--
phar.readonly=0
--FILE--
<?php
Phar::interceptFileFuncs();
$fname = __DIR__ . '/' . basename(__FILE__, '.php') . '.phar.php';
$pname = 'phar://' . $fname;

fopen(array(), 'r');
chdir(__DIR__);
file_put_contents($fname, "blah\n");
file_put_contents("foob", "test\n");
$a = fopen($fname, 'rb');
echo fread($a, 1000);
fclose($a);
unlink($fname);
mkdir($pname . '/oops');
file_put_contents($pname . '/foo/hi', '<?php
$context = stream_context_create();
$a = fopen("foob", "rb", false, $context);
echo fread($a, 1000);
fclose($a);
fopen("../oops", "r");
?>
');
include $pname . '/foo/hi';
?>
===DONE===
--CLEAN--
<?php unlink(__DIR__ . '/' . basename(__FILE__, '.clean.php') . '.phar.php'); ?>
<?php rmdir(__DIR__ . '/poo'); ?>
<?php unlink(__DIR__ . '/foob'); ?>
--EXPECTF--
Warning: fopen() expects parameter 1 to be a valid path, array given in %sfopen_edgecases2.php on line %d
blah
test

Warning: fopen(phar://%sfopen_edgecases2.phar.php/oops): failed to open stream: phar error: path "oops" is a directory in phar://%sfopen_edgecases2.phar.php/foo/hi on line %d
===DONE===
