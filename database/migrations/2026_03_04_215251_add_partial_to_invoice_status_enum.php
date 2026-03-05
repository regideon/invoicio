<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE invoices MODIFY status ENUM('draft','sent','partial','paid','overdue') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::table('invoices')->where('status', 'partial')->update(['status' => 'sent']);
            DB::statement("ALTER TABLE invoices MODIFY status ENUM('draft','sent','paid','overdue') NOT NULL DEFAULT 'draft'");
        }
    }
};
