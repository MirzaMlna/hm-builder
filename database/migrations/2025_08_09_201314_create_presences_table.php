<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('worker_id');
            $table->unsignedBigInteger('presence_schedule_id');
            $table->time('first_check_in')->nullable();
            $table->string('first_check_in_type')->nullable();
            $table->time('second_check_in')->nullable();
            $table->string('second_check_in_type')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_out_type')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            $table->foreign('presence_schedule_id')->references('id')->on('presence_schedules')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
