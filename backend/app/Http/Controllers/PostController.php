<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostSearchService;

class PostController extends Controller
{

    public function __construct(private PostSearchService $postSearchService)
    {
        
        $this->postSearchService = $postSearchService;

    }

    public function getAllPosts(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        $posts = $this->postSearchService->getAllPost($perPage, $page);

        return $this->sendResponse($posts, 200);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $perPage = $request->input('per_page', 15);

        if (empty($query)) {
            return $this->sendResponse($this->postSearchService->getAllPost($perPage), 200);
        }

        $posts = $this->postSearchService->search($query);

        return $this->sendResponse($posts, 200);
    }
    
    public function deleteById(int $id)
    {
        $deleted = $this->postSearchService->deleteById($id);

        if (!$deleted) {
            return $this->sendError('Post not found', 404);
        }

        return $this->sendResponse(null, 'Post deleted successfully', 200);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $user_id = $request->user()->id;

        $data = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => $user_id,
            'tags' => $request->input('tagsInput', []), // Ensure tags is an array
        ];

        $updated = $this->postSearchService->update($id, $data);

        if (!$updated) {
            return $this->sendError('Post not found or update failed', 404);
        }

        return $this->sendResponse(null, 'Post updated successfully', 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        $user_id = $request->user()->id;

        // separate comma-separated tags into an array and insert them into posts_tags table
        $tags = $request->input('tags');
        if ($tags) {
            $tagsArray = explode(',', $tags);
            $tagsArray = array_map('trim', $tagsArray); // Trim whitespace from each tag
            $tagsArray = array_filter($tagsArray); // Remove empty tags
            $request->merge(['tags' => $tagsArray]);
        } else {
            $request->merge(['tags' => []]); // Ensure tags is an empty array if
            // no tags are provided
        }

        $data = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => $user_id,
            'tags' => $request->input('tagsInput'),
        ];

        $created = $this->postSearchService->create($data);

        if (!$created) {
            return $this->sendError('Post creation failed', 400);
        }

        return $this->sendResponse($created, 'Post created successfully', 201);
    }
}
