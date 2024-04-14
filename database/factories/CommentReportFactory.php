<?php

namespace Database\Factories;
use Faker\Factory as faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Comment;
use App\Models\CommentReport;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userIds = User::pluck('id');
        $commentIds = Comment::pluck('id');
        return [
            'user_id' => $this->faker->randomElement($userIds),
            'comment_id' => $this->faker->randomElement($commentIds),
            'comment_report_text' => $this->faker->paragraph,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
