<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/26 0026
 * Time: 17:24
 */

echo "process-start-time:".date("Ymd H:i:s").PHP_EOL;
$workers = [];
$urls = [
    'http://baidu1.com?search=',
    'http://baidu2.com?search=',
    'http://baidu3.com?search=',
    'http://baidu4.com?search=',
    'http://sina.com.cn?search=',
    'http://qq.com?search='
];

for($i = 0; $i < 6; $i++) {
    // 子进程
    $process = new swoole_process(function(swoole_process $worker) use($i, $urls) {
        // curl
        $content = curlData($urls[$i]);
        $worker->write($content.PHP_EOL);
    }, true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

// 打印进程
foreach($workers as $process) {
    echo $process->read();
}

/**
 * 模拟请求URL的内容  1s
 * @param $url
 * @return string
 */
function curlData($url) {
    // curl file_get_contents
    sleep(1);
    return $url . "swoole".PHP_EOL;
}
echo "process-end-time:".date("Ymd H:i:s").PHP_EOL;