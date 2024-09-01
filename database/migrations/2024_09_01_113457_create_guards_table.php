<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardsTable extends Migration
{
    public function up()
    {
        Schema::create('guards', function (Blueprint $table) {
            $table->id();
            $table->string('name');      // Имя дежурного
            $table->string('contact');   // Контактные данные
            $table->string('telegram_link')->nullable(); // Ссылка на Telegram
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guards');
    }
}
