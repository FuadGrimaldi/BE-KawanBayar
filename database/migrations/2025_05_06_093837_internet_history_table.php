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
        Schema::create('internet_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // user pengirim
            $table->foreignId('data_plan_id')->constrained('data_plans'); // data plan yang dibeli
            $table->string('transaction_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internet_histories');
    }
};
