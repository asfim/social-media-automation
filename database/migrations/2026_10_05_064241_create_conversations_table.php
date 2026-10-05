<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_account_id')->constrained('social_accounts')->cascadeOnDelete();
            $table->string('platform'); // facebook, instagram
            $table->string('external_conversation_id')->unique();
            $table->string('customer_name');
            $table->string('customer_id');
            $table->string('customer_avatar')->nullable();
            $table->boolean('ai_active')->default(true);
            $table->string('status')->default('open'); // open, closed, human_review
            $table->integer('lead_score')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
