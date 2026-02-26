<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // ログアウトができる
    public function test_logout_success()
    {
        $user = \App\Models\User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('test12345'),
    ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'test12345',
        ]);

        $response = $this->post('/logout'); 

        $this->assertguest();
    }
}
