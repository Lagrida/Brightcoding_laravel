<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $TagsCount = Tag::count();
        Post::factory(30)
            ->has(Comment::factory(random_int(0, 10))->state(function(array $attributes, Post $post) use ($users) {
                return ['user_id' => $users->random()->id];
            }))->create()->each(function(Post $post) use($TagsCount){
            $take = random_int(0, $TagsCount-2);
            $tagIds = Tag::inRandomOrder()->take($take)->get()->pluck('id');
            $post->tags()->sync($tagIds);
        });
    }
}
