<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;

class EmailAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 会員登録後、認証メールが送信される
    public function test_after_register_send_authentication_email()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);

        $response->assertRedirect('/email/verify');

        $this->assertDatabaseHas('users', [
            'email' => 'bbb@ccc.com'
        ]);

        $response = $this->get('/email/verify');
    }

    // メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function test_transition_authentication_site()
    {
        $response = $this->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);
        $response = $this->get('/email/verify');
        $response->assertStatus(200);

        $user = \App\Models\User::where('email', 'bbb@ccc.com')->first();

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verifyUrl);
        $response->assertStatus(302);
        $response->assertRedirect('/mypage/profile');
    }

    // メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    public function test_authentication_transition_profile()
    {
        $response = $this->post('/register', [
            'name' => 'aaa',
            'email' => 'bbb@ccc.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ]);

        $response = $this->get('/email/verify');
        $response->assertStatus(200);

        $user = \App\Models\User::where('email', 'bbb@ccc.com')->first();

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($verifyUrl)
            ->assertRedirect('/mypage/profile');

        $user->refresh();
            $this->assertNotNull($user->email_verified_at);

        $response = $this->actingAs($user)->get('/mypage/profile');
        $response->assertStatus(200);
    }

}
