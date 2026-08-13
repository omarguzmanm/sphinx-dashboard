<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per user and destination, holding both the inferred activity and
     * the explicit pin. Aggregating on write keeps the table bounded by the
     * number of routes, so there is no visit log to prune later.
     */
    public function up(): void
    {
        Schema::create('user_shortcuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('route');
            $table->unsignedInteger('visits')->default(0);
            $table->timestamp('last_visited_at')->nullable();
            $table->timestamp('pinned_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'route']);
            $table->index(['user_id', 'last_visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_shortcuts');
    }
};
