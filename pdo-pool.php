<?php
include __DIR__ . '/vendor/autoload.php';

use App\PDOPool;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$pool = null;
$server = new Server('127.0.0.1', 9501);

$server->on('request', function (Request $req, Response $resp) {
    $pool = PDOPool::getPool();
    $pdo = $pool->get();
    $stmt = $pdo->prepare("SELECT ? + ?;");
    $a = rand();
    $b = rand();
    if(!$stmt->execute(array($a, $b))){
        throw new \RuntimeException('Execution fail.');
    }

    $result = $stmt->fetchAll();
    $resp->end(print_r($result, true));
    $pool->put($pdo);
});
$server->start();