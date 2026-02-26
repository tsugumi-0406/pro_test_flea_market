<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 「購入する」ボタンを押下すると購入が完了する
    public function test_purchase()
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

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/order', [
            'item_id' => $item->id,
            'account_id' => $account->id,
            'method' => 1,
            'post_code' => '123-4567',
            'address' => 'テスト'
        ]);

        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id
        ]);
    }

    // 購入した商品は商品一覧画面にて「sold」と表示される
    public function test_purchase_sold_label()
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

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/order', [
            'item_id' => $item->id,
            'account_id' => $account->id,
            'method' => 1,
            'post_code' => 123-4567,
            'address' => 'テスト'
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('SOLD');
    }

    // 「プロフィール/購入した商品一覧」に追加されている
    public function test_purchase_mylist()
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

        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/order', [
            'item_id' => $item->id,
            'account_id' => $account->id,
            'method' => 1,
            'post_code' => 123-4567,
            'address' => 'テスト'
        ]);

        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);

        $response->assertSee($item->name);
    }
}
