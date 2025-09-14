<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 16)->default('user');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('users', 'quota_mb')) {
                $table->unsignedInteger('quota_mb')->default(100);
            }
            if (!Schema::hasColumn('users', 'used_mb')) {
                $table->unsignedInteger('used_mb')->default(0);
            }
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role','is_active','quota_mb','used_mb']);
        });
    }
};
