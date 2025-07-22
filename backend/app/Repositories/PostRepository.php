<?php 

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Services\ShardManager;
use Illuminate\Support\Facades\Cache;
use App\Services\SphinxService;

class PostRepository implements PostRepositoryInterface
{
    protected $shardManager;
    protected $sphinxService;

    public function __construct(ShardManager $shardManager, SphinxService $sphinxService)
    {
        $this->sphinxService = $sphinxService;
        $this->shardManager = $shardManager;
    }
    public function all(int $perPage = 15, int $page = 1)
    {
        $allPosts = collect();

        // Get all shard connections from the ShardManager
        $connections = $this->shardManager->getAllShards();

        foreach ($connections as $connection) {
            $posts = Post::on($connection)->with('tags')->get();
            $allPosts = $allPosts->concat($posts);
        }

        // Sort by created_at descending
        $allPosts = $allPosts->sortByDesc('created_at')->values();

        // Paginate manually
        $offset = ($page - 1) * $perPage;
        $paginated = $allPosts->slice($offset, $perPage)->values();

        return [
            'data' => $paginated,
            'total' => $allPosts->count(),
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($allPosts->count() / $perPage),
        ];
    }

    public function find($id, $connection)
    {
        return Post::on($connection)->findOrFail($id);
    }

    public function create(array $data, $connection)
    {
        $post =  Post::on($connection)->create([
            'title' => $data['title'],
            'body' => $data['body'],
            'user_id' => $data['user_id'],
        ]);

        if($data['tags']) {
            $tags = explode(',', $data['tags']);
            $tagIds = [];
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tagModel = \App\Models\Tag::on($connection)->firstOrCreate(['name' => $tag]);
                    $tagIds[] = $tagModel->id;
                }
            }
            // $post->tags()->sync($tagIds);
            $post->tags()->attach($tagIds);
        }
        return $post;
    }

    public function update($id, array $data, $connection)
    {
        $post = Post::on($connection)->findOrFail($id);
        // comma separated tagsInput
        if ($data['tags']) {
            $tags = explode(',', $data['tags']);
            $tagIds = [];
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tagModel = \App\Models\Tag::on($connection)->firstOrCreate(['name' => $tag]);
                    $tagIds[] = $tagModel->id;
                }
            }
            // Detach all current tags, then attach new ones
            $post->tags()->detach();
            $post->tags()->attach($tagIds);
        }
        $post->update($data);
        return $post;
    }

    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return false;
        }

        $post->delete();
        return true;
    }

    public function search(string $keyword)
    {
        $escaped = addslashes($keyword);
        $cacheKey = 'sphinx_search_' . md5($escaped);
        $ttl = now()->addMinutes(5);

        return Cache::remember($cacheKey, $ttl, function () use ($escaped) {
            return $this->sphinxService->search($escaped);
        });
    }
}
