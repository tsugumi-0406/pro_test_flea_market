<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 必要な情報が表示される
    public function test_view_detail_item_data()
    {
        $item = \App\Models\Item::factory()->create();

        $category = \App\Models\Category::factory()->create();
        $item->categories()->attach($category->id);

        $like = \App\Models\Like::factory()->create([
            'item_id' => $item->id,
        ]);

        $likes_count = $item->likes()->count();

        $comment = \App\Models\Comment::factory()->create([
            'item_id' => $item->id,
        ]);

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        $response->assertSee('/storage/' . $item->image);
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee($item->price);
        $response->assertSee((string)$likes_count);
        $response->assertSee($comment->count);
        $response->assertSee($item->description);
        $response->assertSee($category->name);
        $response->assertSee($item->condition);
        $response->assertSee('/storage/' . $comment->account->image);
        $response->assertSee($comment->account->name);
        $response->assertSee($comment->sentence);
    }

    // 複数選択されたカテゴリが表示されているか
    public function test_view_detail_item_categories()
    {
        $item = \App\Models\Item::factory()->create();

        $categories = \App\Models\Category::factory()->count(3)->create();

        foreach ($categories as $category) {
            $item->categories()->attach($category->id);
        }

        $response = $this->get('/item/' . $item->id);
        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
