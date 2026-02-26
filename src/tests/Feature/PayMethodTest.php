<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PayMethodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 小計画面で変更が反映される
    public function test_pay_method_view()
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

        $response->assertSee('コンビニ支払い');
        $response->assertSee('カード支払い');
    }
}
