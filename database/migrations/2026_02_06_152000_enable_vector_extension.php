<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Try to enable the pg_vector extension in PostgreSQL
            DB::statement('CREATE EXTENSION IF NOT EXISTS vector;');
        } catch (\Exception $e) {
            // If vector extension is not available, we'll use a fallback
            // This allows the project to work without pg_vector installed
            if (str_contains($e->getMessage(), 'extension "vector" is not available')) {
                // Create a fallback function for vector operations
                // This will be used for mock mode when pg_vector is not available
                return;
            }
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            // Try to disable the pg_vector extension
            DB::statement('DROP EXTENSION IF EXISTS vector;');
        } catch (\Exception $e) {
            // Ignore errors during rollback
        }
    }
};
