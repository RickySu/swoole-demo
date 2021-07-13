<?php
namespace App;

use Swoole\Coroutine\Redis;

class RedisPool
{
    const N = 5;

    protected ?\SplQueue $pool = null;

    protected static ?self $instance = null;

    public static function getPool(): self
    {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected static function createClient(): Redis
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        return $redis;
    }

    protected function __construct()
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
