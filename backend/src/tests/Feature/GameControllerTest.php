<?php

namespace Tests\Feature;

use App\Enums\GameSessionStatus;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_game_creation_generates_unique_slug(): void
    {
        // two games with the same name
        $game1 = Game::factory()->create(['name' => 'My Own Adventure 2']);
        $game2 = Game::factory()->create(['name' => 'My Own Adventure 2']);

        // slugs should have the game name in the full string
        $this->assertStringStartsWith('my-own-adventure-2-', $game1->slug);
        $this->assertStringStartsWith('my-own-adventure-2-', $game2->slug);
        // slugs should be different
        $this->assertNotEquals($game1->slug, $game2->slug);
    }

    public function test_guest_can_list_only_public_games(): void
    {
        Game::factory(3)->private()
            ->create();
        $publicGames = Game::factory(3)->create();

        $this->getJson('/api/games')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['slug' => $publicGames[0]->slug],
                    ['slug' => $publicGames[1]->slug],
                    ['slug' => $publicGames[2]->slug],
                ],
                'meta' => ['total' => 3],
            ]);
    }

    public function test_user_can_list_all_games(): void
    {
        $privateGames = Game::factory(2)->private()
            ->create();
        $publicGames = Game::factory(2)->create();

        $this->actingAs($this->user)
            ->getJson('/api/games')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['slug' => $privateGames[0]->slug],
                    ['slug' => $privateGames[1]->slug],
                    ['slug' => $publicGames[0]->slug],
                    ['slug' => $publicGames[1]->slug],
                ],
                'meta' => ['total' => 4],
            ]);
    }

    public function test_user_can_list_games_with_their_sessions(): void
    {
        // games without sessions
        $noSessionGames = Game::factory(2)->create();

        $playerData = ['player_id' => $this->user->id];

        // games with sessions
        $sessions = collect([
            GameSession::factory(2)->active()->create($playerData),
            GameSession::factory()->finished()->create($playerData),
            GameSession::factory()->abandoned()->create($playerData),
        ])->flatten();

        $this->actingAs($this->user)
            ->getJson('/api/games')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['slug' => $noSessionGames[0]->slug],
                    ['slug' => $noSessionGames[1]->slug],
                    ['slug' => $sessions[0]->game->slug, 'sessions' => [
                        ['id' => $sessions[0]->id, 'status' => GameSessionStatus::ACTIVE->value]
                    ]],
                    ['slug' => $sessions[1]->game->slug, 'sessions' => [
                        ['id' => $sessions[1]->id, 'status' => GameSessionStatus::ACTIVE->value]
                    ]],
                    ['slug' => $sessions[2]->game->slug, 'sessions' => [
                        ['id' => $sessions[2]->id, 'status' => GameSessionStatus::FINISHED->value]
                    ]],
                    ['slug' => $sessions[3]->game->slug, 'sessions' => [
                        ['id' => $sessions[3]->id, 'status' => GameSessionStatus::ABANDONED->value]
                    ]],
                ],
                'meta' => ['total' => 6],
            ]);
    }

    public function test_guest_can_fetch_game_without_sessions(): void
    {
        $session = GameSession::factory()->create();

        $this->getJson("/api/games/{$session->game->slug}")
            ->assertOk()
            ->assertJsonMissing(['sessions' => []]);
    }

    public function test_user_can_fetch_game_with_sessions(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->getJson("/api/games/{$session->game->slug}")
            ->assertOk()
            ->assertJson(['data' => ['sessions' => [['id' => $session->id]]]]);
    }
}
