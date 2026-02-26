<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('push_subscriptions')) {
            return;
        }

        if (!Schema::hasColumn('push_subscriptions', 'endpoint_hash')) {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                $table->string('endpoint_hash', 64)->nullable()->after('endpoint');
            });
        }

        DB::table('push_subscriptions')
            ->select('id', 'endpoint')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('push_subscriptions')
                        ->where('id', $row->id)
                        ->update([
                            'endpoint_hash' => hash('sha256', $row->endpoint),
                        ]);
                }
            });

        if (!$this->indexExists('push_subscriptions', 'push_subscriptions_endpoint_hash_unique')) {
            Schema::table('push_subscriptions', function (Blueprint $table) {
                $table->unique('endpoint_hash', 'push_subscriptions_endpoint_hash_unique');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('push_subscriptions') || !Schema::hasColumn('push_subscriptions', 'endpoint_hash')) {
            return;
        }

        Schema::table('push_subscriptions', function (Blueprint $table) {
            if ($this->indexExists('push_subscriptions', 'push_subscriptions_endpoint_hash_unique')) {
                $table->dropUnique('push_subscriptions_endpoint_hash_unique');
            }
            $table->dropColumn('endpoint_hash');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $result = DB::selectOne(
            'SELECT COUNT(1) AS aggregate FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
            [$table, $indexName]
        );

        return (int) ($result->aggregate ?? 0) > 0;
    }
};
