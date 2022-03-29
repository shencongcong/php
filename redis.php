<?php

/**
 * This file is part of batata server projects.
 */

// redis 一些好用的操作
// hscan pipeline 锁


/**
 * @author shencongcong
 *
 * 对一些大的hash key进行清理的时候，直接获取，会阻塞影响线上业务
 */
function hsacn(){
    $cursor = '0';
    $redis_conn = init();
    $key = 'database:mail:list';
    //$key = 'database:common-config:i:100:common';
    while ($arrKeys = $redis_conn->hscan($key, $cursor, '', 30)) { // 匹配含有'key'的键
        var_dump(count($arrKeys));
    }
}

/**
 * 多个redisc操作一起执行
 * @author shencongcong
 */
function pipline(){
    $redis_conn = init();
    // 开启管道
    $pipe = $redis_conn->multi(Redis::PIPELINE);
    $a = $pipe->incr('test:a');
    $b = $pipe->incr('test:c');
    var_dump($a,$b);
    $pipe->exec();
}

function lock(){
    //setnx
    // laravel
    $lock = Cache::lock('foo', 10);
    try {
        $lock->block(5);

        // 等待最多5秒后获取的锁...
    } catch (LockTimeoutException $e) {
        // 无法获取锁...
    } finally {
        optional($lock)->release();
    }
}

function init(){
    $redis = new Redis();
    $redis->connect('slots-dev4.batata.love', 6379);
    return $redis;
}

// test
//hsacn();
pipline();