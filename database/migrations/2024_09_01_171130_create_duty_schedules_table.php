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
            $table->unsignedBigInteger('duty_id');
            $table->dateTime('start_at'); // Время начала
            $table->dateTime('end_at'); // Время окончания
            $table->timestamps();

            // Внешний ключ для связи с таблицей дежурных
            $table->foreign('duty_id')->references('id')->on('duties')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('duty_schedules');
    }
}
