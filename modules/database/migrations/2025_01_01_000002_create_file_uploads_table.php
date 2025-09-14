<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('drive_file_id', 128);
            $table->string('drive_file_name', 255);
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('file_uploads');
    }
};
