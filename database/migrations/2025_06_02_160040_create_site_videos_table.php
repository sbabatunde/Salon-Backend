<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');                    // Video title
            $table->text('description')->nullable();   // Video description
            $table->enum('status', ['active', 'inactive'])->default('inactive');  // status
            $table->string('video_path')->nullable();  // stored video file path (nullable if URL used)
            $table->string('video_url')->nullable();   // video URL (nullable if file uploaded)
            $table->timestamps();     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_videos');
    }
};
