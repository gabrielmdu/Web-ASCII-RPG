<?php

namespace Tests\Feature;

use App\Events\GameSessionCreated;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\Scene;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
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
        Event::fake();

        $scene = Scene::factory()->start()
            ->create();

        $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['gameId' => $scene->game->id])
            ->assertCreated();

        $this->assertDatabaseHas('game_sessions', [
            'player_id' => $this->user->id,
            'game_id' => $scene->game->id,
            'current_scene_id' => $scene->id,
        ]);

        Event::assertDispatched(GameSessionCreated::class);
    }

    public function test_user_cannot_create_session_for_missing_game(): void
    {
        $missingGameId = 99;

        $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['gameId' => $missingGameId])
            ->assertJsonValidationErrors('gameId')
            ->assertUnprocessable();

        $this->assertDatabaseCount('game_sessions', 0);
    }

    public function test_user_cannot_create_session_for_same_game(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['gameId' => $session->game->id])
            ->assertJsonValidationErrors('gameId')
            ->assertUnprocessable();

        $this->assertStringContainsString('active session', $response->json('errors.gameId.0'));

        $this->assertDatabaseCount('game_sessions', 1);
    }

    public function test_user_cannot_create_more_than_5_active_sessions(): void
    {
        GameSession::factory(5)->create(['player_id' => $this->user->id]);
        $game = Game::factory()->withScenes()
            ->create();

        $response = $this->actingAs($this->user)
            ->postJson('/api/game-sessions', ['gameId' => $game->id])
            ->assertJsonValidationErrors('gameId')
            ->assertUnprocessable();

        $this->assertStringContainsString('active adventures', $response->json('errors.gameId.0'));

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

    public function test_user_can_select_target_scene(): void
    {
        $game = Game::factory()->withScenes()
            ->create();

        $session = GameSession::factory()->create([
            'player_id' => $this->user->id,
            'game_id' => $game->id,
            'current_scene_id' => $game->startScene->id,
        ]);

        // target chosen by the player
        $targetId = $session->currentChoices[0]->id;

        $this->actingAs($this->user)
            ->postJson("/api/game-sessions/{$session->id}/select-target", ['choiceIndex' => 1])
            ->assertNoContent();

        $this->assertDatabaseHas('game_sessions', [
            'id' => $session->id,
            'game_id' => $game->id,
            'current_scene_id' => $targetId,
        ]);
    }

    public function test_user_cannot_select_not_owned_target_scene(): void
    {
        $otherUser = User::factory()->create();

        $session = GameSession::factory()->create(['player_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->postJson("/api/game-sessions/{$session->id}/select-target", ['choiceIndex' => 1])
            ->assertForbidden();
    }

    public function test_user_cannot_select_target_without_choice_index(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->postJson("/api/game-sessions/{$session->id}/select-target")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('choiceIndex');
    }

    public function test_user_cannot_select_target_with_choice_index_less_than_1(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $lessThanOneIndex = mt_rand(-10, 0);

        $this->actingAs($this->user)
            ->postJson("/api/game-sessions/{$session->id}/select-target", ['choiceIndex' => $lessThanOneIndex])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('choiceIndex');
    }

    public function test_user_cannot_select_target_with_invalid_choice_index(): void
    {
        $session = GameSession::factory()->create(['player_id' => $this->user->id]);

        $invalidIndex = 10;

        $this->actingAs($this->user)
            ->postJson("/api/game-sessions/{$session->id}/select-target", ['choiceIndex' => $invalidIndex])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('choiceIndex');
    }

    public function test_user_cannot_select_target_for_inactive_session(): void
    {
        $session = GameSession::factory()->finished()
            ->create(['player_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->postJson("/api/game-sessions/{$session->id}/select-target", ['choiceIndex' => 1])
            ->assertForbidden();
    }
}
