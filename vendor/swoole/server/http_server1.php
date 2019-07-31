<?php

// 0.0.0.0 监听所有地址
$http = new swoole_http_server("0.0.0.0", 8811);

// 添加测试一：获取参数并打印出来
$http->on('request', function ($request, $response) {
    // 在服务端输出
    print_r($request->get);
    $response->end('test---'.json_encode($request->get));
});

$http->start();