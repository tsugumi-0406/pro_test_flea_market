<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GetUserProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 必要な情報が表示される
    public function test_view_user_data()
    {
        $user = \App\Models\User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $account = \App\Models\Account::factory()->create([
            'user_id' => $user->id,
            'image' => 'test.png',
            'name'  => '山田太郎',
        ]);

        $this->actingAs($user);

        $sellItem = \App\Models\Item::factory()->create([
            'account_id' => $account->id,
        ]);

        $buyItem = \App\Models\Item::factory()->create(); 

        $order = \App\Models\Order::factory()->create([
            'item_id'    => $buyItem->id,
            'account_id' => $account->id,
            'method'     => 1,
            'post_code'  => '123-4567',
            'address'    => 'テスト住所',
        ]);

        $response = $this->get('/mypage?page=sell');

        $response->assertStatus(200);

        $response->assertSee($account->image);
        $response->assertSee($account->name);

        $response->assertSee($sellItem->name);

        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200);

        $response->assertSee($buyItem->name);
    }
}
