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
    // REGULAR USER
    public function it_shows_liked_posts()
    {
        $user = User::factory()->create();
        Auth::login($user);
        $posts = Post::factory(3)->create(['user_id' => $user->id]);
        foreach ($posts as $post) {
            $user->likes()->attach($post);
        }
        $response = $this->actingAs($user)->get(route('users.liked'));
        foreach ($posts as $post) {
            $response->assertSee($post->text);
        }
    }
    /** @test */
    public function user_likes_a_post()
    {
        $user = User::factory()->create();
        Auth::login($user);
        $post = Post::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->post(route('posts.like', $post));
        $response->assertRedirect();
        $this->assertTrue($user->likes->contains($post));
    }

    /** @test */
    public function user_unlikes_a_post()
    {
        $user = User::factory()->create();
        Auth::login($user);
        $post = Post::factory()->create(['user_id' => $user->id]);
        $user->likes()->attach($post);
        $response = $this->actingAs($user)->delete(route('posts.unlike', $post));
        $response->assertRedirect();
        $this->assertFalse($user->likes->contains($post));
    }

    /** @test */
    public function prevents_user_from_unliking_a_post_they_have_not_liked()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->delete(route('posts.unlike', $post));
        $response->assertStatus(302);
    }


    //BLOCKED USER
    public function prevents_blocked_user_from_liking_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $blockedUser = User::factory()->create(['blocked' => 1]);    
        $response = $this->actingAs($blockedUser)->post(route('posts.like', $post));
        $response->assertForbidden();
        $this->assertFalse($blockedUser->likes->contains($post));
    }

    /** @test */
    public function prevents_blocked_user_from_unliking_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $blockedUser = User::factory()->create(['blocked' => 1]);    
        $blockedUser->likes()->attach($post);
        $response = $this->actingAs($blockedUser)->delete(route('posts.unlike', $post));
        $response->assertForbidden();
        $this->assertTrue($blockedUser->likes->contains($post));
    }


    /** @test */
    public function it_prevents_user_from_liking_a_post_created_by_a_blocked_user()
    {
        $user = User::factory()->create();

        $blockedUser = User::factory()->create(['blocked' => 1]);  
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);

        $response = $this->actingAs($user)->post(route('posts.like', $post));

        $response->assertForbidden();
        $this->assertFalse($user->likes->contains($post));

    }

    /** @test */
    public function it_prevents_user_from_unliking_a_post_created_by_a_blocked_user()
    {
        $user = User::factory()->create();
        $blockedUser = User::factory()->create(['blocked' => 1]);  
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);

        $user->likes()->attach($post);

        $response = $this->actingAs($user)->delete(route('posts.unlike', $post));

        $response->assertForbidden();
        $this->assertTrue($user->likes->contains($post));
    }

    /** @test */
    public function prevents_user_from_liking_a_nonexistent_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.like', ['nonexistent_post_id']));

        $response->assertStatus(404);

        $this->assertFalse($user->likes->contains('id', 'nonexistent_post_id'));
    }

    /** @test */
    public function prevents_user_from_unliking_a_nonexistent_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('posts.unlike', ['nonexistent_post_id']));

        $response->assertStatus(404);

        $this->assertFalse($user->likes->contains('id', 'nonexistent_post_id'));
    }


    /** @test */
    public function prevents_guest_from_unliking_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('posts.unlike', $post));

        $response->assertStatus(401);

        // Additional assertion to ensure that the post is not liked by any user
        $this->assertFalse($post->likes()->exists());
    }

    /** @test */
    public function prevents_guest_from_liking_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->post(route('posts.like', $post));

        $response->assertStatus(401);

        // Additional assertion to ensure that the post is not liked by any user
        $this->assertFalse($post->likes()->exists());
    }

}

