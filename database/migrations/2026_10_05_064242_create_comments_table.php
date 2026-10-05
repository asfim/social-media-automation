<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_account_id')->constrained('social_accounts')->cascadeOnDelete();
            $table->string('platform');
            $table->string('external_comment_id')->unique();
            $table->string('external_post_id');
            $table->string('customer_name');
            $table->string('customer_id');
            $table->text('comment_text');
            $table->string('ai_classification')->nullable(); // sales, spam, complaint, general
            $table->string('reply_status')->default('pending'); // pending, replied, hidden, ignored
            $table->text('ai_reply_text')->nullable();
            $table->integer('lead_score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
