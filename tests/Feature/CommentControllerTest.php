<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    public function test_shows_comments_except_from_blocked_user()
    {
        $user = User::factory()->create();
        Auth::login($user);
    
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comments = Comment::factory(3)->create(['user_id' => $user->id,'post_id' => $post->id]);
        $comments->first()->update(['user_id' => $blockedUser->id]);
    
        $response = $this->actingAs($user)->get(route('posts.show', $post));
        $response->assertStatus(200);
        $response->assertViewHas('post', $post);
        foreach ($comments as $comment) {
            if (!$comment->user->blocked) {
                $response->assertSee($comment->comment_text);
            }
        }
    }
    ///REGULAR USER TESTS
    public function test_user_creates_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->get(route('posts.show', $post));
        $response->assertStatus(200);
        $previousUrl = url()->current();
        $commentData = [
            'post_id' => $post->id,
            'comment_text' => 'This is a test comment',
        ];
        $response = $this->post(route('comments.store', $post->id), $commentData);
        $response->assertRedirect($previousUrl);
        $this->assertDatabaseHas('comments', $commentData);
    }
    

    public function testUserCanUpdateOwnComment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id,'post_id' => $post->id]);
        $this->actingAs($user);
        $updatedText = 'Updated comment text';
        $response = $this->put(route('comments.update', $comment), ['comment_text' => $updatedText]);
        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'comment_text' => $updatedText]);
    }

    public function testUserCannotUpdateOtherUserComment()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);
        $this->actingAs($user2);
        $comment = Comment::factory()->create(['user_id' => $user1->id, 'post_id' => $post->id]);
        $response = $this->put(route('comments.update', $comment), ['comment_text' => 'Updated comment text']);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id, 'comment_text' => 'Updated comment text']);
    }
    
    /** @test */
    public function testUserCannotEditOtherUserComment()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $post = Post::factory()->create(['user_id' => $user2->id]);
        $comment = Comment::factory()->create(['user_id' => $user2->id, 'post_id' => $post->id]);
        $response = $this->get(route('comments.edit', $comment));
        $response->assertStatus(403);
    }
    
    /** @test */
    public function testUserCanEditOwnComment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->get(route('comments.edit', $comment));
        $response->assertStatus(200);
    }
    
    public function testUserCannotDeleteOtherUserComment()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $post = Post::factory()->create(['user_id' => $user2->id]);
        $comment = Comment::factory()->create(['user_id' => $user2->id, 'post_id' => $post->id]);
        $response = $this->delete(route('comments.destroy', $comment));
        $response->assertStatus(403);
    }

    /** @test */
    public function testUserCanDeleteOwnComment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->delete(route('comments.destroy', $comment));
        $response->assertRedirect(route('posts.show', $post));
        // You may add more assertions to check for successful deletion
    }

    //GUEST TEST
    public function testGuestCannotCreateComment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $this->assertGuest();

        $response = $this->get(route('posts.show', $post));
        $response->assertStatus(401);
        $commentData = ['comment_text' => 'This is a person'];
        $response = $this->post(route('comments.store', $post), $commentData);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('comments', $commentData);
    }

    /** @test */
    public function testGuestCannotEditComment()
    {
        $user1 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);
        $comment = Comment::factory()->create(['user_id' => $user1->id, 'post_id' => $post->id]);
        $this->assertGuest();
        $response = $this->get(route('comments.edit', $comment));
        $response->assertStatus(401);
    }

    /** @test */
    public function testGuestCannotDeleteComment()
    {
        $user1 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);
        $comment = Comment::factory()->create(['user_id' => $user1->id, 'post_id' => $post->id]);
        $this->assertGuest();
        $response = $this->delete(route('comments.destroy', $comment));
        $response->assertStatus(401);
    }

    /** @test */
    public function testGuestCannotUpdateComment()
    {
        $user1 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);
        $comment = Comment::factory()->create(['user_id' => $user1->id, 'post_id' => $post->id]);
        $updatedData = [
            'comment_text' => 'Updated Text',
        ];

        $response = $this->put(route('comments.update', $comment), $updatedData);
        $response->assertStatus(401);

        $this->assertDatabaseMissing('comments', $updatedData);
    }

    //BLOCKED USER CAN ACCESS OWN POST AND EDIT OWN COMMENT FROM POST

    public function testBlockedUserCanAccessCommentIndexCreate()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $response = $this->actingAs($blockedUser)->get(route('posts.show', $post->id));
        $response->assertStatus(200);
    }

    public function testBlockedUserCanAccessOwnCommentShow()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $comment = Comment::factory()->create(['user_id' => $blockedUser->id,'post_id' => $post->id]);
        $response = $this->actingAs($blockedUser)->get(route('comments.show', $comment));
        $response->assertStatus(200);
    }

    public function testBlockedUserCannotAccessCommentStore()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $commentData = ['comment_text' => 'Test comment'];
        $response = $this->actingAs($blockedUser)->post(route('comments.store', $post), $commentData);
        $response->assertStatus(403);
    }
    public function testBlockedUserCannotAccessOwnCommentEdit()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $comment = Comment::factory()->create(['user_id' => $blockedUser->id,'post_id' => $post->id]);
        $response = $this->actingAs($blockedUser)->get(route('comments.edit', $comment));
        $response->assertStatus(403);
    }
    
    public function testBlockedUserCannotAccessOwnCommentUpdate()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $comment = Comment::factory()->create(['user_id' => $blockedUser->id,'post_id' => $post->id]);
        $commentData = ['comment_text' => 'Updated comment'];
        $response = $this->actingAs($blockedUser)->put(route('comments.update', $comment), $commentData);
        $response->assertStatus(403);
    }
    
    public function testBlockedUserCanAccessOwnCommentDelete()
    {
        $blockedUser = User::factory()->create(['blocked' => 1]);
        $post = Post::factory()->create(['user_id' => $blockedUser->id]);
        $comment = Comment::factory()->create(['user_id' => $blockedUser->id,'post_id' => $post->id]);
    
        $response = $this->actingAs($blockedUser)->delete(route('comments.destroy', $comment));
    
        $response->assertStatus(302);
    }
    

    //TESTS CHARACTER LIMIT
    public function testUserCreatesCommentWithinCharacterLimit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $content = $this->faker->words(20, true);
        $previousUrl = url()->current();
        $response = $this->actingAs($user)
                        ->post(route('comments.store'), [
                            'comment_text' => $content,
                            'post_id' => $post->id,
                        ]);
                        
        $response->assertRedirect($previousUrl)->assertSessionHas('success');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'comment_text' => $content,
        ]);
    }
    
    public function testUserCreatesCommentAboveCharacterLimit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $characterLimit = 1000;
        $content = $this->faker->words($characterLimit + 10, true);
        $response = $this->actingAs($user)
                         ->post(route('comments.store'), [
                             'comment_text' => $content,
                             'post_id' => $post->id,
                         ]);
        $response->assertSessionHasErrors('comment_text');
        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'comment_text' => $content,
        ]);
    }
    

    public function testUserUpdatesOwnCommentWithinCharacterLimit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id,'post_id' => $post->id]);
        $content = $this->faker->words(20, true);
        $response = $this->actingAs($user)
                            ->put(route('comments.update', $comment), [
                                'comment_text' => $content,
                            ]);
        $response->assertRedirect(route('posts.show', $comment->post_id))->assertSessionHas('success');
                    
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'user_id' => $user->id,
            'comment_text' => $content,
        ]);
    }

    public function testUserUpdatesOwnCommentAboveCharacterLimit()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['user_id' => $user->id,'post_id' => $post->id]);
        $characterLimit = 1000;
        $content = $this->faker->words($characterLimit + 10, true);
        $response = $this->actingAs($user)
                        ->put(route('comments.update', $comment), [
                            'comment_text' => $content,
                        ]);
        $response->assertStatus(302)->assertSessionHasErrors('comment_text');
        $comment->refresh();
        $this->assertNotEquals($content, $comment->text);
    }

        
    public function test_user_cannot_create_comment_without_specifying_post()
    {
        $user = User::factory()->create();
        $commentData = [
            'comment_text' => 'This is a comment text.',
        ];
        $response = $this->actingAs($user)->post(route('comments.store'), $commentData);
        $response->assertSessionHasErrors('post_id');
    }

    public function test_UserCannotUpdateNonExistentComment()
    {
        $user = User::factory()->create();
        $nonExistentCommentId = 9999;
        $newContent = $this->faker->sentence();
    
        $response = $this->actingAs($user)
                         ->put(route('comments.update', $nonExistentCommentId), [
                             'comment_text' => $newContent,
                         ]);
        $response->assertStatus(404);
    }
    
    public function test_user_cannot_delete_nonexistent_comment()
    {
        $user = User::factory()->create();
        $nonExistentCommentId = 9999;

        $response = $this->actingAs($user)
                         ->delete(route('comments.destroy', $nonExistentCommentId));
        $response->assertStatus(404);
    }

    /** @test */
    public function test_user_cannot_edit_nonexistent_comment()
    {
        $user = User::factory()->create();
        $nonExistentCommentId = 9999;

        $response = $this->actingAs($user)
                         ->get(route('comments.edit', $nonExistentCommentId));
        $response->assertStatus(404);
    }
    /** @test */
    public function test_user_cannot_show_nonexistent_comment()
    {
        $user = User::factory()->create();
        $nonExistentCommentId = 9999;

        $response = $this->actingAs($user)
                         ->get(route('comments.show', $nonExistentCommentId));
        $response->assertStatus(404);
    }
}










