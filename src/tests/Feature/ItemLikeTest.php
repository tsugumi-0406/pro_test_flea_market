<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemLikeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // いいねアイコンを押下することによって、いいねした商品として登録することができる。
    public function test_item_like_register()
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

        $response = $this->post('/items/' . $item->id . '/like', [
            'account_id' => '$account->id',
            'item_id' => '$item->id',
        ]);

        $this->assertDatabaseHas('likes', [
            'account_id' => $account->id,
            'item_id' => $item->id, 
        ]);
    }

        // 追加済みのアイコンは色が変化する
        public function test_item_like_iconColor_change()
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

            $response = $this->post('/items/' . $item->id . '/like', [
                'account_id' => '$account->id',
                'item_id' => '$item->id',
            ]);

            $response->assertJson([
                'likes_count' => 1,
            ]);
        }

        // 再度いいねアイコンを押下することによって、いいねを解除することができる。
        public function test_item_like_delete()
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

            $response = $this->post('/items/' . $item->id . '/like', [
                'account_id' => '$account->id',
                'item_id' => '$item->id',
            ]);

            $response = $this->post('/items/' . $item->id . '/like', );

            $response->assertJson([
                'likes_count' => 0,
            ]);
        }
}
