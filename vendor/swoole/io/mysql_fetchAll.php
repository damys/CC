<?php
use Swoole\Coroutine as co;
co::create(function() {
    $db = new co\MySQL();
    $server = array(
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => '123456',
        'database' => 'test',
    );

    $ret1 = $db->connect($server);
    $stmt = $db->prepare('SELECT * FROM test LIMIT 1');
    $stmt->execute();
//    var_dump($stmt->fetchAll());
    while($ret = $stmt->fetch())
    {
        var_dump($ret);
    }
});