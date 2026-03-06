<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class GameSearchTest extends TestCase
{
    use RefreshDatabase;

    #[DataProviderExternal(GameSearchDataProvider::class, 'gamesForStringSearchProvider')]
    public function test_filter_games_by_search_string(string $search, array $games, array $creators, array $expected): void
    {
        $user = User::factory()->create();

        if (!empty($creators)) {
            $users = User::factory()->createMany($creators);

            foreach (array_keys($games) as $key) {
                $games[$key]['creator_id'] = $users[$key]->id;
            }
        }

        Game::factory()->createMany($games);

        $response = $this->actingAs($user)
            ->getJson("/api/games?search={$search}");

        $response->assertOk()
            ->assertJsonCount(count($expected), 'data');

        foreach ($expected as $exp) {
            $response->assertJsonPath(key($exp), $exp[key($exp)]);
        }
    }

    #[DataProviderExternal(GameSearchDataProvider::class, 'gamesForOrderProvider')]
    public function test_sort_games_by_order_and_direction(string $sort, bool $asc, array $games, array $creators, array $expected): void
    {
        $ascInt = $asc ? 1 : 0;
        $user = User::factory()->create();

        if (!empty($creators)) {
            $users = User::factory()->createMany($creators);

            foreach (array_keys($games) as $key) {
                $games[$key]['creator_id'] = $users[$key]->id;
            }
        }

        Game::factory()->createMany($games);

        $response = $this->actingAs($user)
            ->getJson("/api/games?sort={$sort}&asc={$ascInt}");

        $response->assertOk();

        foreach ($expected as $exp) {
            $response->assertJsonPath(key($exp), $exp[key($exp)]);
        }
    }

    public function test_show_only_public_games(): void
    {
        $user = User::factory()->create();
        Game::factory(7)->private()
            ->create();
        Game::factory(7)->create();

        $this->actingAs($user)
            ->getJson('/api/games?public=1')
            ->assertOk()
            ->assertJsonCount(7, 'data');
    }

    public function test_handle_pagination_correctly(): void
    {
        $user = User::factory()->create();
        Game::factory()->count(86)->create();

        $this->actingAs($user)
            ->getJson('/api/games?page=9')
            ->assertOk()
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure(['links', 'meta']);
    }
}
