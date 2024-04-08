<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserGifControllerTest extends TestCase
{
    use WithFaker;

    public function test_search_gif()
    {
        $response = $this->getJson('/api/gif/search', [
            'search' => $this->faker->word,
            'limit' => 10,
            'offset' => 0,
        ]);

        $response->assertStatus(200);
    }

    public function test_search_gif_by_id()
    {
        $response = $this->getJson('/api/gif/searchById', [
            'id' => 'mlvseq9yvZhba',
        ]);

        $response->assertStatus(200);
    }

    public function test_store_favorite_gif()
    {
        $user =  User::create(['email' => 'admintest2@mail.com', 'password' => Hash::make('secret')]);
        $response = $this->postJson('/api/gif/save/favorite', [
            'user_id' => $user->id,
            'gif_id' => 'mlvseq9yvZhba',
            'alias' => 'Favorite Gif',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'alias',
                'gif_id'
            ]);
    }
}
