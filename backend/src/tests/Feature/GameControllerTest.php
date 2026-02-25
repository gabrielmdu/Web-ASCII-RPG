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

    public function test_guest_can_list_only_public_games(): void
    {
        Game::factory(3)->private()
            ->create();
        $publicGames = Game::factory(3)->create();

        $this->getJson('/api/games')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['id' => $publicGames[0]->id],
                    ['id' => $publicGames[1]->id],
                    ['id' => $publicGames[2]->id],
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
                    ['id' => $privateGames[0]->id],
                    ['id' => $privateGames[1]->id],
                    ['id' => $publicGames[0]->id],
                    ['id' => $publicGames[1]->id],
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
                    ['id' => $noSessionGames[0]->id],
                    ['id' => $noSessionGames[1]->id],
                    ['id' => $sessions[0]->game_id, 'sessions' => [
                        ['id' => $sessions[0]->id, 'status' => GameSessionStatus::ACTIVE->value]
                    ]],
                    ['id' => $sessions[1]->game_id, 'sessions' => [
                        ['id' => $sessions[1]->id, 'status' => GameSessionStatus::ACTIVE->value]
                    ]],
                    ['id' => $sessions[2]->game_id, 'sessions' => [
                        ['id' => $sessions[2]->id, 'status' => GameSessionStatus::FINISHED->value]
                    ]],
                    ['id' => $sessions[3]->game_id, 'sessions' => [
                        ['id' => $sessions[3]->id, 'status' => GameSessionStatus::ABANDONED->value]
                    ]],
                ],
                'meta' => ['total' => 6],
            ]);
    }

    public function test_guest_can_fetch_game_without_sessions(): void
    {
        $session = GameSession::factory()->create();

        $this->getJson("/api/games/{$session->game_id}")
            ->assertOk()
            ->assertJsonMissing(['sessions' => []]);
    }

    public function test_user_can_fetch_game_with_sessions(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->getJson("/api/games/{$session->game_id}")
            ->assertOk()
            ->assertJson(['data' => ['sessions' => [['id' => $session->id]]]]);
    }
}
