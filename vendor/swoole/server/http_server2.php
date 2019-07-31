<?php

// 0.0.0.0 监听所有地址
$http = new swoole_http_server("0.0.0.0", 8811);


/**
 * // 测试二：
 * https://wiki.swoole.com/wiki/page/783.html
 * 配置静态文件根目录，与enable_static_handler配合使用。
 * 设置document_root并设置enable_static_handler为true后，
 * 底层收到Http请求会先判断document_root路径下是否存在此文件，
 * 如果存在会直接发送文件内容给客户端，不再触发onRequest回调。
 *
 * http://192.168.249.130:8811/index.html
 * 会显示index.html的内容，如此目录index.html 不存在，则显示后以request 内容
 */
$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/home/wwwroot/default/swoole/page",
    ]
);



//添加测试一：获取参数并打印出来
$http->on('request', function ($request, $response) {
    // 在服务端输出
//    print_r($request->get);

    $response->cookie("name",'json', time() + 1800);
    $response->end('test---'.json_encode($request->get));
});

$http->start();