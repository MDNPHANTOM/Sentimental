<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostLikeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_liked_posts()
    {
        // Create a user for testing
        $user = User::factory()->create();
        Auth::login($user);

        // Create some posts
        $posts = Post::factory(3)->create();

        // Like some posts
        foreach ($posts as $post) {
            $user->likes()->attach($post);
        }

        // Visit the route
        $response = $this->actingAs($user)->get(route('show_liked_posts'));

        // Assert that the response contains the posts
        foreach ($posts as $post) {
            $response->assertSee($post->title); // Assuming the post title is displayed
        }
    }

    /** @test */
    public function it_likes_a_post()
    {
        // Create a user for testing
        $user = User::factory()->create();
        Auth::login($user);

        // Create a post
        $post = Post::factory()->create();

        // Like the post
        $response = $this->actingAs($user)->post(route('like_post', $post));

        // Assert that the post is liked
        $response->assertRedirect();
        $this->assertTrue($user->likes->contains($post));
    }

    /** @test */
    public function it_unlikes_a_post()
    {
        // Create a user for testing
        $user = User::factory()->create();
        Auth::login($user);

        // Create a post
        $post = Post::factory()->create();

        // Like the post
        $user->likes()->attach($post);

        // Unlike the post
        $response = $this->actingAs($user)->delete(route('unlike_post', $post));

        // Assert that the post is unliked
        $response->assertRedirect();
        $this->assertFalse($user->likes->contains($post));
    }
}

