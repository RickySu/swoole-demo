<?php
namespace App;

use Swoole\Coroutine\Redis;

class RedisPool
{
    const N = 5;

    protected \SplQueue $pool;

    static public function createClient(): Redis
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        return $redis;
    }

    public function __construct()
    {
        $this->pool = new \SplQueue();

        for($i = 0; $i < self::N; $i++) {
            $client = self::createClient();
            $this->pool->push($client);
        }
    }

    public function put(Redis $redis)
    {
        $this->pool->push($redis);
    }

    public function get()
    {
        if (count($this->pool) > 0) {
            return $this->pool->pop();
        }
        sleep(0.01);
        return $this->get();
    }

}
