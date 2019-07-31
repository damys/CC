<?php

// 0.0.0.0 监听所有地址
$http = new swoole_http_server("0.0.0.0", 8811);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/home/wwwroot/default/swoole/page",
    ]
);

$http->on('request', function($request, $response) {
    $data = [
        'date:' => date("Ymd H:i:s"),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];


    $filename = __DIR__ . "/access.txt";
    Swoole\Coroutine::create(function () use ($filename, $data)
    {
        $r =  Swoole\Coroutine::writeFile($filename, json_encode($data).PHP_EOL, FILE_APPEND);
        var_dump($r);
    });


    $response->cookie("name",'json', time() + 1800);
    $response->end('test---'.json_encode($request->get));
});

$http->start();