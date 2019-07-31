<?php


/**
 * 读取文件  swoole_async_readfile 4.0 版本不支持，需要用：Coroutine readFile
 * __DIR__
 * 文件不存在会返回false
 * 成功打开文件立即返回true
 * 数据读取完毕后会回调指定的callback函数。
 */
//函数风格
//$result = swoole_async_readfile(__DIR__."/test.txt", function($filename, $fileContent) {
//    echo "filename:".$filename.PHP_EOL;
//    echo "content:".$fileContent.PHP_EOL;
//});


//命名空间风格
//$result = Swoole\Async::readfile(__DIR__."/test.txt", function($filename, $fileContent) {
//    echo "filename:".$filename.PHP_EOL;
//    echo "content:".$fileContent.PHP_EOL;
//});

//------------------ 4.0 版本
use Swoole\Coroutine as co;
$filename  = __DIR__ . "/test.txt";
co::create(function () use ($filename)
{
    $f =  co::readFile($filename);
    var_dump($f);
});


echo "start".PHP_EOL;