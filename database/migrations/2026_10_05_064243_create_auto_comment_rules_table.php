<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_comment_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('hide_spam')->default(true);
            $table->boolean('reply_to_leads')->default(true);
            $table->text('generic_reply')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_comment_rules');
    }
};
