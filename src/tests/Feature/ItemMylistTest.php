<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemMylistTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // いいねした商品だけが表示される
    public function test_mylist_like_item_view()
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

        $like = \App\Models\Like::factory()->create([
            'account_id' => $account->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee($item->name);
    }
    
    // 購入済み商品は「Sold」と表示される
    public function test_mylist_item_sold_label()
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

        $orders = \App\Models\Order::factory()->create([
            'item_id' => $item->id,
        ]);

        $like = \App\Models\Like::factory()->create([
            'account_id' => $account->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('SOLD');
    }

    // 未認証の場合は何も表示されない
    public function test_hide_mylist_noLogin_user()
    {
        $item = \App\Models\Item::factory()->create();

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee($item->name);
    }
}
