<?php

use App\Models\Scene;
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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Scene::class)->constrained();
            $table->foreignIdFor(Scene::class, 'target_id')->nullable()->constrained();
            $table->text('text');
            $table->json('settings');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
