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
    use WithFaker;
    
    //BLOCKED USER TESTS
    public function test_shows_posts_except_from_blocked_user()
    {
        $user = User::factory()->create();
        Auth::login($user);
    
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $posts = Post::factory(3)->create(['user_id' => $user->id]);
        $posts->first()->update(['user_id' => $blockedUser->id]);
    
        $response = $this->actingAs($user)->get(route('posts.index'));
    
        foreach ($posts as $post) {
            if (!$post->user->blocked) {
                $response->assertSee($post->text);
            }
        }
    }

    
    ///REGULAR USER TESTS
    public function test_user_creates_post()
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

    //GUEST TEST
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


    public function testBlockedUserCanAccessIndexCreate()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $response = $this->actingAs($blockedUser)->get(route('posts.index'));
        $response->assertStatus(200);
    }


    public function testBlockedUserCanAccessOwnPostShow()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $response = $this->actingAs($blockedUser)->get(route('posts.show', $post));
        $response->assertStatus(200);
    }
    public function testBlockedUserCannotAccessStore()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $postData = ['text' => 'Test post'];
        $response = $this->actingAs($blockedUser)->post(route('posts.store'), $postData);
        $response->assertStatus(403);
    }

    public function testBlockedUserCannotAccessOwnEdit()
    {
        
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $response = $this->actingAs($blockedUser)->get(route('posts.edit', $post));
        $response->assertStatus(403);
    }

    public function testBlockedUserCannotAccessUpdate()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $postData = ['text' => 'Updated post'];
        $response = $this->actingAs($blockedUser)->put(route('posts.update', $post), $postData);
        $response->assertStatus(403);
    }

    public function testBlockedUserCanAccessDelete()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);

        $response = $this->actingAs($blockedUser)->delete(route('posts.destroy', $post));

        $response->assertStatus(302);
    }

    public function testUserCreatesPostWithinCharacterLimit()
    {
        $user = User::factory()->create();
        $content = $this->faker->words(20, true);
        $response = $this->actingAs($user)
                        ->post(route('posts.store'), [
                            'text' => $content,
                        ]);
        $response->assertRedirect(route('posts.index'))
                ->assertSessionHas('success');
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'text' => $content,
        ]);
    }

    public function testUserCreatesPostAboveCharacterLimit()
    {
        $user = User::factory()->create();
        $characterLimit = 1000;
        $content = $this->faker->words($characterLimit + 10, true);
        $response = $this->actingAs($user)
                        ->post(route('posts.store'), [
                            'text' => $content,
                        ]);
        $response->assertSessionHasErrors('text');
        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id,
            'text' => $content,
        ]);
    }


    public function testUserUpdatesOwnPostWithinCharacterLimit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id,]);
        $content = $this->faker->words(20, true);
        $response = $this->actingAs($user)
                        ->put(route('posts.update', $post), [
                            'text' => $content,
                        ]);
        $response->assertRedirect(route('posts.index'))
                ->assertSessionHas('success');
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $user->id,
            'text' => $content,
        ]);
    }

    public function testUserUpdatesOwnPostAboveCharacterLimit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id,]);
        $characterLimit = 1000;
        $content = $this->faker->words($characterLimit + 10, true);
        $response = $this->actingAs($user)
                        ->put(route('posts.update', $post), [
                            'text' => $content,
                        ]);
        $response->assertStatus(302)->assertSessionHasErrors('text');
        $post->refresh();
        $this->assertNotEquals($content, $post->text);
    }


    public function testUserCannotUpdatesNonExistentPost()
    {
        $user = User::factory()->create();
        $nonExistentPostId = 9999;
        $newContent = $this->faker->sentence();

        $response = $this->actingAs($user)
                        ->put(route('posts.update', $nonExistentPostId), [
                            'text' => $newContent,
                        ]);

        $response->assertStatus(404);
    }


    public function testUserCannotDeleteNonExistentPost()
    {
        $user = User::factory()->create();
        $nonExistentPostId = 9999;
        $newContent = $this->faker->sentence();

        $response = $this->actingAs($user)
                        ->delete(route('comments.destroy', $nonExistentPostId));

        $response->assertStatus(404);
    }

    public function testUserCannotEditNonExistentPost()
    {
        $user = User::factory()->create();
        $nonExistentPostId = 9999;
        $newContent = $this->faker->sentence();

        $response = $this->actingAs($user)
                        ->get(route('comments.edit', $nonExistentPostId));

        $response->assertStatus(404);
    }
    public function testUserCannotShowNonExistentPost()
    {
        $user = User::factory()->create();
        $nonExistentPostId = 9999;
        $newContent = $this->faker->sentence();

        $response = $this->actingAs($user)
                        ->get(route('comments.show', $nonExistentPostId));

        $response->assertStatus(404);
    }


}
