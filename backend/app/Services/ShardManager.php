<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ShardManager
{
    protected $shards;
    public function __construct()
    {
        $this->shards = [
            'mysql_shard_1' => 'mysql_shard_1',
            'mysql_shard_2' => 'mysql_shard_2',
        ];
    }
    public function getShardConnection($userId): string
    {
        return $userId % 2 === 0 ? 'mysql_shard_1' : 'mysql_shard_2';
    }

    public function getShardDb($userId)
    {
        $connection = $this->getShardConnection($userId);
        return DB::connection($connection);
    }

    public function getAllShards(): array
    {
        return $this->shards;
    }

    public function getShardDbByPostId($postId)
    {
        $userId = Post::on('mysql_shard_1')->find($postId)?->user_id
                ?? Post::on('mysql_shard_2')->find($postId)?->user_id;

        if (!$userId) {
            throw new \Exception("Post not found in any shard.");
        }

        return $this->getShardConnection($userId);
    }

}
