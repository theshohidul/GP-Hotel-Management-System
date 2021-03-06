<?php


namespace App\Utility\Cache\Drivers;


class Redis extends \Stash\Driver\Redis
{
    /**
     * {@inheritdoc}
     */
    public function clear($key = null)
    {
        if (is_null($key)) {
            $this->redis->flushDB();

            return true;
        }

        $keyString = $this->makeKeyString($key, true);
        $keyReal = $this->makeKeyString($key);
        $this->redis->incr($keyString); // increment index for children items
        $this->redis->del($keyReal); // remove direct item.
        $this->keyCache = array();

        return true;
    }

}