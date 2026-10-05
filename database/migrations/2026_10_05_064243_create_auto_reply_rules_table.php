<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_reply_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trigger_intent'); // pricing, greeting, etc
            $table->text('response_template');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_reply_rules');
    }
};
