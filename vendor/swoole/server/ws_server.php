<?php
/**
 * https://segmentfault.com/a/1190000014572291
 *
 * 使用命名空间
 * new swoole_websocket_server("0.0.0.0", 9912);
 * new Swoole\WebSocket\Server("0.0.0.0", 9912);
 */

$server = new Swoole\WebSocket\Server("0.0.0.0", 9912);
//配置静态文件根目录，可选
$server->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/home/wwwroot/default/swoole/page"
    ]
);

//监听websocket连接打开事件
$server->on('open', 'onOpen');

/**
 * @param $server
 * @param $request  是一个Http请求对象，包含了客户端发来的握手请求信息
 */
function onOpen($server, $request) {
    print_r($request->fd . ' ');
}

/**
 * 监听ws消息事件
 * $frame 是swoole_websocket_frame对象，包含了客户端发来的数据帧信息
 */
$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    // echo 到服务端窗口显示
    echo "receive from {$frame->fd}:{$frame->data}, opcode:{$frame->opcode}, fin:{$frame->finish}\n";

    // 到客户端显示
    $server->push($frame->fd, "message-push-success");
});

$server->on('close', function ($ser, $fd) {
    // echo 到服务端窗口显示
    echo "client {$fd} closed\n";
});

$server->start();


/**
 * 测试：tcp, http
 * 客户端代码端口号是不变的： ws://192.168.249.130:9912
 * http://192.168.249.130:9912/ws_client.html   tcp
 * http://192.168.249.130:8811/ws_client.html   http
 */