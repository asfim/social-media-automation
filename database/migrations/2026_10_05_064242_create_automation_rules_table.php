<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name');
            $table->string('platform'); // facebook, instagram, all
            $table->string('trigger_type'); // exact_match, contains, any_message
            $table->text('keywords')->nullable(); // comma separated
            $table->string('response_type'); // static, ai_generated
            $table->text('static_response')->nullable();
            $table->boolean('ai_enabled')->default(false);
            $table->integer('delay_seconds')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_rules');
    }
};
