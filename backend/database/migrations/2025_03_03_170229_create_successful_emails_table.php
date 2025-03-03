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
        Schema::create('successful_emails', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('affiliate_id')->index(); // Affiliate filtering
            $table->text('envelope');
            $table->string('from')->index(); // Querying by sender
            $table->text('subject');
            $table->string('dkim')->nullable();
            $table->string('SPF')->nullable();
            $table->float('spam_score')->nullable();
            $table->longText('email');
            $table->longText('raw_text')->nullable();
            $table->string('sender_ip', 50)->nullable()->index(); // Searching by sender IP
            $table->text('to');
            $table->integer('timestamp')->index(); // Sorting or querying by time
            $table->softDeletes()->index(); // Soft delete optimization
            $table->timestamps();

            // Additional indexes
            $table->index(['from', 'timestamp']); // Compound index for filtering by sender and time
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('successful_emails');
    }
};
