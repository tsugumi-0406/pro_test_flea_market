<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UpdateUserProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_view_user_data_update()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $account = \App\Models\Account::factory()->create([
            'user_id' => $user->id,
            'name' => '山田太郎',
            'image' => 'test.png',
            'post_code' => '123-4567',
            'address' => 'テスト住所',
            'building' => 'テスト建物',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee('/storage/' . $account->image);
        $response->assertSee('山田太郎');
        $response->assertSee('123-4567');
        $response->assertSee('テスト住所');
        $response->assertSee('テスト建物');
    }
}
