<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('username')->unique();
            $table->string('password');

            // role: opd / publik
            $table->string('role')->default('opd');

            // kalau role opd, wajib isi opd_id
            $table->unsignedBigInteger('opd_id')->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('opd_id')->references('id')->on('opds')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};