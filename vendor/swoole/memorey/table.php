<?php
/**
swoole_table一个基于共享内存和锁实现的超高性能，并发数据结构
使用场景：用于解决多进程/多线程数据共享和同步加锁问题
进程结束后内存表会自动释放
 */


// 创建内存表
$table = new Swoole\table(1024);

// 内存表增加一列
$table->column('id', $table::TYPE_INT, 4);
$table->column('name', $table::TYPE_STRING, 64);
$table->column('age', $table::TYPE_INT, 3);
$table->create();

$table->set('imooc', ['id' => 1, 'name'=> 'json', 'age' => 30]);
// 另外一种方案
$table['imooc2'] = [
    'id' => 2,
    'name' => 'tom',
    'age' => 31,
];

$table->decr('imooc', 'age', 2);
print_r($table['imooc']);        // key ,value
print_r($table->get('imooc'));   // array
print_r($table['imooc2']);

//echo "delete start:".PHP_EOL;
//$table->del('imooc2');
//
//print_r($table['imooc2']);
