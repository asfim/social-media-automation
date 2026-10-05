<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('platform'); // facebook, instagram, manual
            $table->string('profile_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('interested_service')->nullable();
            $table->integer('lead_score')->default(0);
            $table->string('lead_status')->default('new'); // new, contacted, interested, negotiation, won, lost
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_contact')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
