<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function foreignKeyExists(string $table, string $fkName): bool
    {
        $dbName = DB::getDatabaseName();

        $rows = DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = ?
               AND TABLE_NAME = ?
               AND CONSTRAINT_TYPE = 'FOREIGN KEY'
               AND CONSTRAINT_NAME = ?",
            [$dbName, $table, $fkName]
        );

        return count($rows) > 0;
    }

    public function up(): void
    {
        // table opds dibuat setelah bookings, jadi FK dipasang di sini
        if (Schema::hasTable('opds') && Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'opd_id')) {
            if (!$this->foreignKeyExists('bookings', 'bookings_opd_id_foreign')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->foreign('opd_id')
                        ->references('id')
                        ->on('opds')
                        ->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            // drop foreign by column (lebih aman daripada nama)
            Schema::table('bookings', function (Blueprint $table) {
                try { $table->dropForeign(['opd_id']); } catch (\Throwable $e) {}
            });
        }
    }
};