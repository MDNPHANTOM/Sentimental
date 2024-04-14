<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_posts_except_from_blocked_user()
    {
        $user = User::factory()->create();
        Auth::login($user);
    
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $posts = Post::factory(3)->create();
        $posts->first()->update(['user_id' => $blockedUser->id]);
    
        $response = $this->actingAs($user)->get(route('posts.index'));
    
        foreach ($posts as $post) {
            if (!$post->user->blocked) {
                $response->assertSee($post->text);
            }
        }
    }
    
    /** @test */
    public function shows_post_and_comments_except_from_blocked_user()
    {
        $user = User::factory()->create();
        Auth::login($user);
    
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create();
        $comments = Comment::factory(3)->create(['post_id' => $post->id]);
        $comments->first()->update(['user_id' => $blockedUser->id]);
    
        $response = $this->actingAs($user)->get(route('posts.show', $post));
    
        $response->assertSee($post->text);
        foreach ($comments as $comment) {
            if (!$comment->user->blocked) {
                $response->assertSee($comment->text);
            }
        }
    }
    
    /** @test */
    public function user_creates_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('posts.index'));
        $response->assertStatus(200);

        $postData = ['text' => 'This is a test post'];
        $response = $this->post(route('posts.store'), $postData);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', $postData);
    }
    

/** @test */
public function testUserCanUpdateOwnPost()
{
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);
    $updatedText = 'Updated post text';
    $response = $this->put(route('posts.update', $post), ['text' => $updatedText]);
    $response->assertRedirect(route('posts.index'));
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'text' => $updatedText]);
}
/** @test */
public function testUserCannotUpdateOtherUserPost()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user1->id]);
    $this->actingAs($user2);
    $response = $this->put(route('posts.update', $post), ['text' => 'Updated post text']);
    $response->assertStatus(403);
    $this->assertDatabaseMissing('posts', ['id' => $post->id, 'text' => 'Updated post text']);
}
/** @test */
public function testUserCannotEditOtherUserPost()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $this->actingAs($user1);
    $post = Post::factory()->create(['user_id' => $user2->id]);
    $response = $this->get(route('posts.edit', $post));
    $response->assertStatus(403);
}
/** @test */
public function testUserCanEditOwnPost()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create(['user_id' => $user->id]);
    $response = $this->get(route('posts.edit', $post));
    $response->assertStatus(200);
}
/** @test */
public function testUserCannotDeleteOtherUserPost()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $this->actingAs($user1);
    $post = Post::factory()->create(['user_id' => $user2->id]);
    $response = $this->delete(route('posts.destroy', $post));
    $response->assertStatus(403);
}
/** @test */
public function testUserCanDeleteOwnPost()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create(['user_id' => $user->id]);
    $response = $this->delete(route('posts.destroy', $post));
    $response->assertRedirect(route('posts.index'));
    // You may add more assertions to check for successful deletion
}

/** @test */
public function testGuestCannotCreatePost()
{
    $this->assertGuest();

    $response = $this->get(route('posts.index'));
    $response->assertStatus(401);
    $postData = ['text' => 'This is a person'];
    $response = $this->post(route('posts.store'), $postData);
    $response->assertStatus(401);
    $this->assertDatabaseMissing('posts', $postData);
}

/** @test */
public function testGuestCannotEditPost()
{
    $user1 = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user1->id]);
    $response = $this->get(route('posts.edit', $post));
    $response->assertStatus(401);
}
/** @test */
public function testGuestCannotDeletePost()
{
    $user1 = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user1->id]);
    $response = $this->delete(route('posts.destroy', $post));
    $response->assertStatus(401);
}



public function testGuestCannotUpdatePost()
{
    $user1 = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user1->id]);
    $updatedData = [
        'text' => 'Updated Text',
    ];

    $response = $this->put(route('posts.update', $post), $updatedData);
    $response->assertStatus(401);

    $this->assertDatabaseMissing('posts', $updatedData);
}


public function testShowPost()
{
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $comments = Comment::factory()->count(3)->create(['post_id' => $post->id]);
    $blockedUser = User::factory()->create(['blocked' => 1]);
    $blockedComments = Comment::factory()->count(2)->create([
        'post_id' => $post->id,
        'user_id' => $blockedUser->id,
    ]);
    $response = $this->get(route('posts.show', $post));
    $response->assertStatus(200);
    $response->assertViewHas('post', $post);
    $response->assertSeeInOrder($comments->pluck('content')->toArray());
    $response->assertDontSee($blockedComments->pluck('content')->toArray());
}

}
