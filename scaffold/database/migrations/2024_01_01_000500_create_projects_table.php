<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('sport_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('culture_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->unsignedBigInteger('target_amount')->default(0);
            $table->unsignedBigInteger('current_amount')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->enum('status', ['draft', 'reviewing', 'published', 'closed'])->default('draft');
            $table->string('prefecture')->nullable();
            $table->string('city')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
