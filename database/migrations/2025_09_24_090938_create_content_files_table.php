<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_files', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('gen_random_uuid()'))->primary();
            $table->string('filename', 255);
            $table->text('content'); // md file content here
            $table->string('file_path', 500)->nullable();
            $table->jsonb('metadata')->default('{}');
            $table->string('status', 50)->default('pending'); // pending, processing, completed, failed
            $table->string('source_site', 100)->default('bonus.ca'); // bonus.ca, bonusfinder.com, etc.
            $table->string('file_hash', 64)->nullable(); // for deduplication
            $table->timestampTz('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->string('location')->nullable(); // example "us_nj" (country+region)
            $table->timestampsTz();
            
            // Indexes for performance
            $table->index('filename');
            $table->index('status');
            $table->index('source_site');
            $table->index('file_hash');
            $table->index('location');
            $table->index('processed_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_files');
    }
};
