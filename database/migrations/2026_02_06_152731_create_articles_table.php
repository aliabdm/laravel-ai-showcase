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
        // Check if vector extension is available
        $vectorAvailable = false;
        try {
            $result = DB::select("SELECT 1 FROM pg_extension WHERE extname = 'vector'");
            $vectorAvailable = count($result) > 0;
        } catch (\Exception $e) {
            // If we can't check, assume it's not available
            $vectorAvailable = false;
        }

        Schema::create('articles', function (Blueprint $table) use ($vectorAvailable) {
            $table->id();
            $table->string('title');
            $table->text('content');

            if ($vectorAvailable) {
                $table->vector('embedding', dimensions: 768)->index();
            } else {
                $table->text('embedding')->nullable();
                $table->index('embedding'); 
            }


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
