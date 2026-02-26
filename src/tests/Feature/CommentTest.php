<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // ログイン済みのユーザーはコメントを送信できる
    public function test_login_user_comment_send()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $account = \App\Models\Account::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create();

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/comment', [
            'sentence' => 'テストコメント',
            'item_id' => $item->id,
            'account_id' => $account->id,
        ]);

        $response->assertSee([
            'comments_count' => 1,
        ]);
    }

    // ログイン前のユーザーはコメントを送信できない
    public function test_logout_user_comment_send_error()
    {
        $item = \App\Models\Item::factory()->create();

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/comment', [
            'sentence' => 'テストコメント',
            'item_id' => $item->id,
            'account_id' => 999,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'sentence' => 'テストコメント',
        ]);
    }

    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_sentence_required_validation()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $account = \App\Models\Account::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create();

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/comment', [
            'sentence' => '',
            'item_id' => $item->id,
            'account_id' => $account->id,
        ]);

        $response->assertSessionHasErrors([
            'sentence' => 'コメントを入力してください',
        ]);
    }

    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_comment_sentence_max_validation()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('test12345'),
            ]);

        $account = \App\Models\Account::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create();

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/comment', [
            'sentence' => 'ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ',
            'item_id' => $item->id,
            'account_id' => $account->id,
        ]);

        $response->assertSessionHasErrors([
            'sentence' => 'コメントは255文字以内で入力してください',
        ]);
    }
}
