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

class AdminControllerTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_can_block_a_user()
    {
        $admin = User::factory()->create(['isAdmin' => 1]);
        $user = User::factory()->create(['blocked' => 0]);
    
        $response = $this->actingAs($admin)->post(route('admin.block_user', $user));
        $user->refresh();
        $response->assertStatus(405);
        $this->assertEquals(0, $user->blocked);
        
    }
    


}
