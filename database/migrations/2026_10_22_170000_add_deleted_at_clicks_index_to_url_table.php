<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cover SUM(clicks) WHERE deleted_at IS NULL so the dashboard total is read from the
     * index alone. It also serves every query the plain deleted_at index did, so that goes.
     */
    public function up(): void
    {
        Schema::table('url', function (Blueprint $table) {
            $table->index(['deleted_at', 'clicks']);
            $table->dropIndex(['deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('url', function (Blueprint $table) {
            $table->index('deleted_at');
            $table->dropIndex(['deleted_at', 'clicks']);
        });
    }
};
