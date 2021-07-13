<?php
include __DIR__ . '/vendor/autoload.php';

use App\RedisPool;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$pool = null;
$server = new Server('127.0.0.1', 9501);
$server->on('workerStart', function (Server $server) use (&$pool) {
    $pool = new RedisPool();
});

$server->on('workerExit', function (Server $server) use (&$pool) {
    unset($pool);
});

$server->on('request', function (Request $req, Response $resp) use (&$pool) {
    $redis = $pool->get();
    if ($redis === false) {
        $resp->end("ERROR");
        return;
    }
    $result = $redis->getKeys('*');
    $resp->end(var_export($result, true));
    $pool->put($redis);
});
$server->start();