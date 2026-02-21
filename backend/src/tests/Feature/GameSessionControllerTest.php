<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\Scene;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_can_fetch_owned_sessions(): void
    {
        $sessions = GameSession::factory(2)->create(['player_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->getJson('/api/game-sessions')
            ->assertOk()
            ->assertJson([
                'data' => [
                    ['id' => $sessions[0]->id],
                    ['id' => $sessions[1]->id],
                ],
                'meta' => ['total' => 2],
            ]);
    }

    public function test_user_can_create_session(): void
    {
        $scene = Scene::factory()->start()
            ->create();

        $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['game_id' => $scene->game->id])
            ->assertCreated();

        $this->assertDatabaseHas('game_sessions', [
            'player_id' => $this->user->id,
            'game_id' => $scene->game->id,
            'current_scene_id' => $scene->id,
        ]);
    }

    public function test_user_cannot_create_session_for_missing_game(): void
    {
        $missingGameId = 99;

        $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['game_id' => $missingGameId])
            ->assertJsonValidationErrors('game_id')
            ->assertUnprocessable();

        $this->assertDatabaseCount('game_sessions', 0);
    }

    public function test_user_cannot_create_session_for_same_game(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['game_id' => $session->game->id])
            ->assertJsonValidationErrors('game_id')
            ->assertUnprocessable();

        $this->assertStringContainsString('active session', $response->json('errors.game_id.0'));

        $this->assertDatabaseCount('game_sessions', 1);
    }

    public function test_user_cannot_create_more_than_5_active_sessions(): void
    {
        GameSession::factory(5)->create(['player_id' => $this->user->id]);
        $game = Game::factory()->withScenes()
            ->create();

        $response = $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['game_id' => $game->id])
            ->assertJsonValidationErrors('game_id')
            ->assertUnprocessable();

        $this->assertStringContainsString('active adventures', $response->json('errors.game_id.0'));

        $this->assertDatabaseCount('game_sessions', 5);
    }

    public function test_user_can_fetch_owned_session(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/game-sessions');

        $response->assertOk()
            ->assertJson(['data' => [['id' => $session->id]]]);
    }
}
