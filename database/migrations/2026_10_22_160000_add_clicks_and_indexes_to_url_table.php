<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store the click count on each url instead of counting the view table on every
     * request, and index the columns the redirect, rate limit and dashboard filter on.
     */
    public function up(): void
    {
        Schema::table('url', function (Blueprint $table) {
            $table->unsignedInteger('clicks')->default(0)->after('private');

            // Not unique: older data contains duplicate short codes
            $table->index('short_url');
            $table->index(['ip', 'created_at']);
            $table->index('clicks');
            $table->index('deleted_at');
        });

        // Backfill from the existing views
        if (DB::getDriverName() === 'mysql') {
            DB::statement('
                UPDATE url
                JOIN (SELECT url_id, COUNT(*) AS c FROM view WHERE deleted_at IS NULL GROUP BY url_id) v
                    ON v.url_id = url.id
                SET url.clicks = v.c
            ');
        } else {
            DB::statement('
                UPDATE url SET clicks = (
                    SELECT COUNT(*) FROM view WHERE view.url_id = url.id AND view.deleted_at IS NULL
                )
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('url', function (Blueprint $table) {
            $table->dropIndex(['short_url']);
            $table->dropIndex(['ip', 'created_at']);
            $table->dropIndex(['clicks']);
            $table->dropIndex(['deleted_at']);
            $table->dropColumn('clicks');
        });
    }
};
