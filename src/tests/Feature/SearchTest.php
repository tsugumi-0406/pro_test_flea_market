<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 「商品名」で部分一致検索ができる
    public function test_search_item_view_index()
    {
        $item = \App\Models\Item::factory()->create([
            'name' => 'keyword'
        ]);

        $response = $this->get('/search?keyword=key');

        $response->assertStatus(200);

        $response->assertSee('keyword');
    }

    // 「商品名」で部分一致検索ができる
    public function test_search_item_view_mylist()
    {
        $item = \App\Models\Item::factory()->create([
            'name' => 'keyword'
        ]);

        $response = $this->get('/search?keyword=key');

        $response->assertStatus(200);

        $response->assertSee('keyword');

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('keyword');
    }
}
