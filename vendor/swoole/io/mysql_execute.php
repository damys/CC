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
//    $stmt = $db->prepare('SELECT * FROM test WHERE id=? and username=?');
//    $stmt = $db->prepare('update test set username=? where id = 1');
    $stmt = $db->prepare('insert into test(username) VALUES (?)');

    if ($stmt == false)
    {
        var_dump($db->errno, $db->error);
    }
    else
    {
//        $ret2 = $stmt->execute(array(1, 'singwa'));
        $ret2 = $stmt->execute(array('singwa2'));
        var_dump($ret2);

//        $ret3 = $stmt->execute(array(2, 'singwa2'));
        $ret3 = $stmt->execute(array('singwa3'));
        var_dump($ret3);
    }
});