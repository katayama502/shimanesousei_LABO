<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_org_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('tier_id')->nullable()->constrained('sponsorship_tiers')->nullOnDelete();
            $table->unsignedBigInteger('amount');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'canceled', 'completed'])->default('pending');
            $table->enum('payment_method', ['invoice', 'bank', 'offline'])->default('invoice');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
    }
};
