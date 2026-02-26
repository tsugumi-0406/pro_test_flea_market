<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）
    public function test_view_user_data()
    {
        $user = \App\Models\User::factory()->create();

        $account = \App\Models\Account::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/sell');
        $response->assertStatus(200);

        $file = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');

        $response = $this->post('/listing', [
            'image'       => $file,
            'condition'   => 1,
            'name'        => 'テスト',
            'brand'       => 'テストブランド',
            'description' => 'これはテストです',
            'price'       => 100000,
            'category_id' => 1,    // ← required のため必須
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト'
        ]);
    }
}