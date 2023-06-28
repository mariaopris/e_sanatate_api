<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('diagnostics', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('type');
            $table->Text('symptoms')->nullable();
            $table->Text('result');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnostics');
    }
};
