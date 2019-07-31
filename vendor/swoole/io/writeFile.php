<?php


//use Swoole\Coroutine as co;
//$filename = __DIR__ . "/defer_client.php";
//co::create(function () use ($filename)
//{
//    $r =  co::writeFile($filename,"hello swoole!");
//    var_dump($r);
//});


$filename = __DIR__ . "/test.txt";
Swoole\Coroutine::create(function () use ($filename)
{
    $r =  Swoole\Coroutine::writeFile($filename, "hello swoole!".PHP_EOL, FILE_APPEND);
    var_dump($r);
});