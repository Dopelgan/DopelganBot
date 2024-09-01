<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDutySchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('duty_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('guard_id');
            $table->date('date'); // Дата дежурства
            $table->time('start'); // Время начала
            $table->time('end'); // Время окончания
            $table->timestamps();

            // Внешний ключ для связи с таблицей охранников
            $table->foreign('guard_id')->references('id')->on('guards')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('duty_schedules');
    }
}
