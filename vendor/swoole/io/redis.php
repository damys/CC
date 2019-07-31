<?php
const REDIS_SERVER_HOST = '127.0.0.1';
const REDIS_SERVER_PORT = 6379;


go(function (){
    $redis1 = new Swoole\Coroutine\Redis();
    $redis1->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
    $redis1->setDefer();
    $redis1->set('key3', 'value3');
    $result1 = $redis1->recv();

    $redis2 = new Swoole\Coroutine\Redis();
    $redis2->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
    $redis2->setDefer();
    $redis2->get('key3');
    $result2 = $redis2->recv();

    var_dump($result1, $result2);
});