<?php

namespace Database\Factories;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        $postIds = Post::pluck('id');
        $userIds = User::pluck('id');


        return [
            'user_id' => $this->faker->randomElement($userIds),
            'post_id' => $this->faker->randomElement($postIds),
            'commment_text' => $this->faker->sentence,
        ];
    }
}
