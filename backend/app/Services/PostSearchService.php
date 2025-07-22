<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\ShardManager;

class PostSearchService
{
    protected $postRepository;
    protected $shardManager;
    protected $sphinxService;

    /**
     * Create a new PostSearchService via sphinx instance.
     */
    public function __construct(PostRepositoryInterface $postRepository, ShardManager $shardManager, SphinxService $sphinxService)
    {
        $this->postRepository = $postRepository;
        $this->shardManager = $shardManager;
        $this->sphinxService = $sphinxService;
    }

    public function search(string $query): array
    {
        $results = $this->postRepository->search($query);

        $posts = [];
        foreach($results as $result){
            $connection = $this->shardManager->getShardDbByPostId($result->id);
            $post = $this->postRepository->find($result->id, $connection);
            if ($post) {
                $posts[] = $post;
            }
        }

        return $posts;
    }

    public function create(array $data): Post
    {
        $connection = $this->shardManager->getShardDb($data['user_id']);

        return $this->postRepository->create($data, $connection->getName());
    }

    public function update(int $id, array $data): Post
    {
        $connection = $this->shardManager->getShardDb($data['user_id']);
        
        return $this->postRepository->update($id, $data, $connection->getName());
    }

    public function getAllPost(int $perPage = 15, int $page = 1)
    {
        return $this->postRepository->all($perPage, $page);
    }

    public function deleteById(int $id): bool
    {
        return $this->postRepository->delete($id);
    }
}