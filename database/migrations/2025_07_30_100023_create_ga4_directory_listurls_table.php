<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ga4_directory_listurls', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ga4_directory_url');
    }
};
