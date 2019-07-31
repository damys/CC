<?php

class Ws
{
    CONST HOST = "0.0.0.0";
    CONST PORT = 9912;    // task 跟客户端ws 一致
    public $ws = null;

    public function __construct() {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->set(
            [
                'worker_num' => 2,
                'task_worker_num' => 2,

                // 会显示index.html的内容，如此目录index.html 不存在，则显示后以request 内容
                'enable_static_handler' => true,
                'document_root' => "/home/wwwroot/default/swoole/page"
            ]
        );
        //注册Server的事件回调函数
        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);

        // task 投递异步任务之后程序会继续往下执行，不会等待任务执行完后再继续向下执行
        // 场景：执行耗时的操作（发送邮件 广播等）
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);

        $this->ws->on("close", [$this, 'onClose']);
        $this->ws->start();
    }


    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request) {
        echo $request->fd.PHP_EOL;

        if(intval($request->fd) === 2) {
            // 每隔2s 执行一次
//            swoole_timer_tick(2000, function ($timer_id) {
//                echo "tick-2000ms: $timer_id".PHP_EOL;
//            });
        }
    }


    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame) {
        // 接收客户端send 的数据
        echo "client-send-data:{$frame->data}\n";

        // todo 10s
        $data = [
            'task' => 1,
            'fd' => $frame->fd,
        ];
        // 投递异步任务
        // 程序会继续往下执行，不会等待任务执行完后再继续向下执行
        $ws->task($data);

        //3000ms后执行此函数
//        swoole_timer_after(3000, function () use ($ws, $frame) {
//            echo "after 3000ms.\n";
//            $ws->push($frame->fd, 'time after...');
//        });

        // 客户端会马上收到以下信息
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }


    /**
     * @param $serv
     * @param $taskId
     * @param $workerId
     * @param $data
     * @return string
     */
    public function onTask($serv, $taskId, $workerId, $data) {
        print_r($data);
        // 耗时场景 10s
        sleep(10);
        return "on task finish"; // 告诉worker，并返回给onFinish的$data
    }


    /**
     * task 设定场景时间后执行
     * @param $serv
     * @param $taskId
     * @param $data
     */
    public function onFinish($serv, $taskId, $data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";   // 为onTask return 的数据
    }


    /**
     * close
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd) {
        echo "clientid:{$fd}\n";
    }
}

$obj = new Ws();