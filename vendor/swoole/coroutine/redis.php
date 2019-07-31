<?php
/**
 * 协程
 *
 */

$http = new  swoole_http_server('0.0.0.0', 9001);

$http->on('request', function($request, $response) {
    // 获取redis 里面 的key的内容， 然后输出浏览器
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $value = $redis->get($request->get['key']);

    // 测试：
    // 1. 启动redis, 在解压目录执行 redis-server
    // 2. 设置测试数据：在解压目录执行redis-cli
    // 3. 在浏览喊叫请求：http://192.168.249.130:9001/?key=name

    // mysql.....

    // 执行时间取它们中最大的：time = max(redis,mysql)

    $response->header("Content-Type", "text/plain");
    $response->end($value);
});

$http->start();