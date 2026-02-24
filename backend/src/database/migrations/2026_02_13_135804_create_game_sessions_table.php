<?php

use App\Enums\GameSessionStatus;
use App\Models\Game;
use App\Models\Scene;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class, 'player_id')->constrained();
            $table->foreignIdFor(Game::class)->constrained();
            $table->foreignIdFor(Scene::class, 'current_scene_id')->constrained();
            $table->enum('status', GameSessionStatus::cases())->default(GameSessionStatus::ACTIVE);
            $table->json('settings')->nullable();
            $table->json('history')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['game_id', 'player_id', 'status']);
            $table->index(['game_id', 'status', 'updated_at']);
            $table->index(['game_id', 'current_scene_id', 'status']);
            $table->index(['updated_at', 'game_id', 'player_id']);
            $table->index(['created_at', 'game_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
