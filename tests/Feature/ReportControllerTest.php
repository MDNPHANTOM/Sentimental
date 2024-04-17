<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\Comment;
use App\Models\CommentReport;
class ReportControllerTest extends TestCase
{
    use RefreshDatabase;


    //USER SIDE
    /** @test */
    public function user_can_successfully_report_a_post()
    {
        $reportingUser = User::factory()->create();
        $postOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);

        $response = $this->actingAs($reportingUser)
                         ->post(route('posts.report_post', $post), ['post_report_text' => 'This post violates the guidelines']);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('post_reports', [
            'user_id' => $reportingUser->id,
            'post_id' => $post->id,
            'post_report_text' => 'This post violates the guidelines',
        ]);
    }
    
    /** @test */
    public function user_must_provide_report_text_to_report_a_post()
    {
        $user = User::factory()->create();
        $postOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);

        $response = $this->actingAs($user)
                            ->post(route('posts.report_post', $post), []);

        $response->assertSessionHasErrors('post_report_text');
        $this->assertDatabaseMissing('post_reports', ['post_id' => $post->id]);
    }


    /** @test */
    public function total_number_of_reports_for_user_is_incremented_when_post_is_reported()
    {
        $postOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);
        $initialReportCount = $postOwner->reported;
        $reportingUser = User::factory()->create();
        $response = $this->actingAs($reportingUser)
                         ->post(route('posts.report_post', $post), ['post_report_text' => 'This post violates the guidelines']);
        $postOwner->refresh();
        $this->assertEquals($initialReportCount + 1, $postOwner->reported);
    }

    /** @test */
    public function total_number_of_reports_for_post_is_incremented_when_it_is_reported()
    {
        $postOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);
        $initialReportCount = $post->post_reports;
        $reportingUser = User::factory()->create();
        $response = $this->actingAs($reportingUser)
                         ->post(route('posts.report_post', $post), ['post_report_text' => 'This post violates the guidelines']);
        $post->refresh();
        $this->assertEquals($initialReportCount + 1, $post->post_reports);
    }
    /** @test */
    public function user_is_redirected_to_posts_index_page_after_reporting_post_successfully()
    {
        $postOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);
        $reportingUser = User::factory()->create();
        $response = $this->actingAs($reportingUser)
                         ->post(route('posts.report_post', $post), ['post_report_text' => 'This post violates the guidelines']);
        $response->assertRedirect(route('posts.index'));
    }
    /** @test */
    public function guest_cannot_report_a_post()
    {
        $postOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);
        $response = $this->post(route('posts.report_post', $post), ['post_report_text' => 'This post violates the guidelines']);
        $response->assertStatus(401);
    }

    /** @test */
    public function total_number_of_reports_for_comment_is_incremented_when_it_is_reported()
    {
        $commentOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $commentOwner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $commentOwner->id]);
        $initialReportCount = $comment->comment_reports;
        $reportingUser = User::factory()->create();
        $response = $this->actingAs($reportingUser)
                         ->post(route('comments.report_comment', $comment), ['comment_report_text' => 'This comment violates the guidelines']);
        $comment->refresh();
        $this->assertEquals($initialReportCount + 1, $comment->comment_reports);
    }

    /** @test */
    public function user_is_redirected_to_comments_index_page_after_reporting_comment_successfully()
    {
        $commentOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $commentOwner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $commentOwner->id]);
        $reportingUser = User::factory()->create();
        $response = $this->actingAs($reportingUser)
                         ->post(route('comments.report_comment', $comment), ['comment_report_text' => 'This comment violates the guidelines']);
        $response->assertRedirect(route('posts.index'));
    }

    /** @test */
    public function guest_cannot_report_a_comment()
    {
        $commentOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $commentOwner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $commentOwner->id]);
        $response = $this->post(route('comments.report_comment', $comment), ['comment_report_text' => 'This comment violates the guidelines']);
        $response->assertStatus(401);
    }


    /** @test */
    public function user_can_successfully_report_a_comment()
    {
        $reportingUser = User::factory()->create();
        $commentOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $commentOwner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $commentOwner->id]);

        $response = $this->actingAs($reportingUser)
                            ->post(route('comments.report_comment', $comment), ['comment_report_text' => 'This comment violates the guidelines']);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('comment_reports', [
            'user_id' => $reportingUser->id,
            'comment_id' => $comment->id,
            'comment_report_text' => 'This comment violates the guidelines',
        ]);
    }

    /** @test */
    public function user_must_provide_report_text_to_report_a_comment()
    {
        $user = User::factory()->create();
        $commentOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $commentOwner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $commentOwner->id]);

        $response = $this->actingAs($user)
                            ->post(route('comments.report_comment', $comment), []);

        $response->assertSessionHasErrors('comment_report_text');
        $this->assertDatabaseMissing('comment_reports', ['comment_id' => $comment->id]);
    }


    /** @test */
    public function total_number_of_reports_for_user_is_incremented_when_comment_is_reported()
    {
        $commentOwner = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $commentOwner->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $commentOwner->id]);
        $initialReportCount = $commentOwner->reported;
        $reportingUser = User::factory()->create();
        $response = $this->actingAs($reportingUser)
                            ->post(route('comments.report_comment', $comment), ['comment_report_text' => 'This comment violates the guidelines']);
        $commentOwner->refresh();
        $this->assertEquals($initialReportCount + 1, $commentOwner->reported);
    }


    //ADMIN SIDE

    /** @test */
    public function admin_can_delete_other_users_post_reports()
    {
        $admin = User::factory()->create(['isAdmin' => 1]);
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->actingAs($admin)
                         ->delete(route('reports.delete_post_report', $postReport));
        $this->assertDatabaseMissing('post_reports', ['id' => $postReport->id]);
        $response->assertRedirect();
    }

    /** @test */
    public function admin_can_delete_other_users_comment_reports()
    {
        $admin = User::factory()->create(['isAdmin' => 1]);
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $response = $this->actingAs($admin)
                            ->delete(route('reports.delete_comment_report', $commentReport));
        $this->assertDatabaseMissing('comment_reports', ['id' => $commentReport->id]);
        $response->assertRedirect();
    }
    /** @test */
    public function user_cannot_delete_post_reports()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->actingAs($user)
                         ->delete(route('reports.delete_post_report', $postReport));
        $response->assertStatus(403);
        $this->assertDatabaseHas('post_reports', ['id' => $postReport->id]);
    }
    /** @test */
    public function user_cannot_delete_comment_reports()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $response = $this->actingAs($user)
                         ->delete(route('reports.delete_comment_report', $commentReport));
        $response->assertStatus(403);
        $this->assertDatabaseHas('comment_reports', ['id' => $commentReport->id]);
    }

    /** @test */
    public function guest_cannot_delete_post_reports()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->delete(route('reports.delete_post_report', $postReport));
        $response->assertStatus(403);
        $this->assertDatabaseHas('post_reports', ['id' => $postReport->id]);
    }

    /** @test */
    public function guest_cannot_delete_comment_reports()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $response = $this->delete(route('reports.delete_comment_report', $commentReport));
        $response->assertStatus(403);
        $this->assertDatabaseHas('comment_reports', ['id' => $commentReport->id]);
    }

    /** @test */
    public function user_reported_count_decreases_when_post_report_is_deleted()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $user = User::find($postReport->post->user_id);
        $initialReportedCount = $user->reported;
        $this->delete(route('reports.delete_post_report', $postReport));
        $user->refresh();
        $this->assertEquals($initialReportedCount - 1, $user->reported);
    }

    /** @test */
    public function post_report_count_decreases_when_post_report_is_deleted()
    {
        $user = User::factory()->create(['isAdmin' => 1]);
        $post1 = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post1->id]);
        $post = Post::find($postReport->post_id);
        $initialReportCount = $post->post_reports;
        $this->delete(route('reports.delete_post_report', $postReport));
        $post->refresh();
        $this->assertEquals($initialReportCount - 1, $post->post_reports);
    }


    /** @test */
    public function post_report_deletion_redirects_back()
    {
        $user = User::factory()->create(['isAdmin' => 1]);
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->delete(route('reports.delete_post_report', $postReport));
        $response->assertRedirect();
    }

    /** @test */
    public function success_message_is_flashed_after_deleting_post_report()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $response = $this->delete(route('reports.delete_post_report', $postReport));
        $response->assertSessionHas('success', 'Deleted Report on post');
    }


   /** @test */
   public function deleting_nonexistent_post_report_returns_not_found()
   {
       $admin = User::factory()->create(['isAdmin' => 1]);
       $nonExistentPostReportId = 9999;
       $response = $this->actingAs($admin)->delete(route('reports.delete_post_report', $nonExistentPostReportId));
       $response->assertNotFound();
   }

    /** @test */
    public function unauthorized_user_cannot_delete_post_report()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $unauthorizedUser = User::factory()->create();
        $response = $this->actingAs($unauthorizedUser)
                         ->delete(route('reports.delete_post_report', $postReport));

        $response->assertStatus(403);
    }


    /** @test */
    public function deleting_last_post_report_does_not_affect_user_reported_count()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $postReport = PostReport::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);
        $initialReportedCount = $user->reported;
        $this->delete(route('reports.delete_post_report', $postReport));
        $user->refresh();
        $this->assertEquals($initialReportedCount, $user->reported);
    }


    /** @test */
    public function deleting_nonexistent_comment_report_returns_not_found()
    {
        $nonExistentCommentReportId = 9999;
        $response = $this->delete(route('reports.delete_comment_report', $nonExistentCommentReportId));
        $response->assertNotFound();
    }

    /** @test */
    public function unauthorized_user_cannot_delete_comment_report()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $unauthorizedUser = User::factory()->create();
        $response = $this->actingAs($unauthorizedUser)
                         ->delete(route('reports.delete_comment_report', $commentReport));

        $response->assertStatus(403);
    }

    /** @test */
    public function deleting_last_comment_report_does_not_affect_user_reported_count()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $initialReportedCount = $user->reported;
        $this->delete(route('reports.delete_comment_report', $commentReport));
        $user->refresh();
        $this->assertEquals($initialReportedCount, $user->reported);
    }



    /** @test */
    public function comment_report_count_decreases_when_comment_report_is_deleted()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $comment = Comment::find($commentReport->comment_id);
        $initialReportCount = $comment->comment_reports;
        $this->delete(route('reports.delete_comment_report', $commentReport));
        $comment->refresh();
        $this->assertEquals($initialReportCount - 1, $comment->comment_reports);
    }

    /** @test */
    public function comment_report_deletion_redirects_back()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $response = $this->delete(route('reports.delete_comment_report', $commentReport));
        $response->assertRedirect();
    }

    /** @test */
    public function success_message_is_flashed_after_deleting_comment_report()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $response = $this->delete(route('reports.delete_comment_report', $commentReport));
        $response->assertSessionHas('success', 'Deleted Report on post');
    }

    /** @test */
    public function comment_report_is_deleted_successfully()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create(['post_id' => $post->id,'user_id' => $user->id]);
        $commentReport = CommentReport::factory()->create(['user_id' => $user->id, 'comment_id' => $comment->id]);
        $response = $this->delete(route('reports.delete_comment_report', $commentReport));
        $this->assertDeleted($commentReport);
        $response->assertRedirect();
    }


}
