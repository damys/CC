<?php
/**
以树状图显示进程间的关系:pstree -p 进程id
启动成功后会创建worker_num+2个进程。Master进程+Manager进程+serv->worker_num个Worker进程
管道：进程和进程间的一个桥梁
 */
$process = new swoole_process(function(swoole_process $pro) {
    // todo
    // php redis.php
    $pro->exec("/usr/local/php/bin/php", [__DIR__.'/../server/http_server1.php']);
}, false);

$pid = $process->start();
echo $pid . PHP_EOL;

swoole_process::wait();