<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateAddressTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_address_update_purchasepage_view()
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

        $response = $this->get('/purchase/address/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/update/address/' . $item->id, [
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト建物',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/purchase/' . $item->id);

        $response = $this->followingRedirects()->get('/purchase/' . $item->id);

        $response->assertSee('123-4567');
    }

    // 購入した商品に送付先住所が紐づいて登録される
    public function test_address_update_relation_item()
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

        $response = $this->get('/purchase/address/' . $item->id);
        $response->assertStatus(200);

        $response = $this->post('/update/address/' . $item->id, [
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト建物',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/purchase/' . $item->id);

        $response = $this->get('/purchase/' . $item->id);
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
        $response->assertSee('テスト建物');

        $response = $this->post('/order', [
            'item_id' => $item->id,
            'account_id' => $account->id,
            'method' => 1,
            'post_code' => '987-6543',
            'address' => '注文時住所',
        ]);

        $this->assertDatabaseHas('orders', [
            'post_code' => '987-6543',
            'address'   => '注文時住所',
        ]);
    }
}
