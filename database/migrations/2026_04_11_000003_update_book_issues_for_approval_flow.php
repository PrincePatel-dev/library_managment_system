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
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE book_issues MODIFY issue_date DATE NULL");
            DB::statement("ALTER TABLE book_issues MODIFY due_date DATE NULL");
            DB::statement("ALTER TABLE book_issues MODIFY status ENUM('pending','issued','returned','overdue','rejected') NOT NULL DEFAULT 'issued'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE book_issues SET status = 'issued' WHERE status IN ('pending','rejected')");
            DB::statement("UPDATE book_issues SET issue_date = COALESCE(issue_date, CURDATE()), due_date = COALESCE(due_date, DATE_ADD(CURDATE(), INTERVAL 14 DAY))");
            DB::statement("ALTER TABLE book_issues MODIFY issue_date DATE NOT NULL");
            DB::statement("ALTER TABLE book_issues MODIFY due_date DATE NOT NULL");
            DB::statement("ALTER TABLE book_issues MODIFY status ENUM('issued','returned','overdue') NOT NULL DEFAULT 'issued'");
        }
    }
};
