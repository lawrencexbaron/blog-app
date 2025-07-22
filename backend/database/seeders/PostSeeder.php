<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ShardManager;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $shards = app(ShardManager::class)->getAllShards();

        User::create([
            'id' => 1,
            'name' => 'Sample User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        for ($i = 1; $i <= 100; $i++) {
                Post::create([
                    'user_id' => 1,
                    'title' => "Sample Post {$i}",
                    'body' => "This is the body of sample post {$i}.",
                ]);
            }
    }
}
