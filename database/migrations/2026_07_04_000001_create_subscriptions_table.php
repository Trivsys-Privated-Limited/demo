<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // restaurant user
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // jo admin ne diya
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('months');
            $table->decimal('amount', 10, 2)->default(0); // admin ne kitna charge kiya
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
