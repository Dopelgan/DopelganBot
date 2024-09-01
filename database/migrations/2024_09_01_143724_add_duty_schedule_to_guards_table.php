<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDutyScheduleToGuardsTable extends Migration
{
    public function up()
    {
        Schema::table('guards', function (Blueprint $table) {
            $table->json('duty_schedule')->nullable();
        });
    }

    public function down()
    {
        Schema::table('guards', function (Blueprint $table) {
            $table->dropColumn('duty_schedule');
        });
    }
}

