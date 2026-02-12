<?php

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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'creator_id')->constrained();
            $table->string('name', 80);
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('version', 16);
            $table->boolean('public')->default(false);
            $table->dateTime('last_modified')->default(now());
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('public');
            $table->index('last_modified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
