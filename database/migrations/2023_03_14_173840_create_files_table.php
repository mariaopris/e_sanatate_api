<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->Text('address')->nullable();
            $table->Date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->Text('other_affections')->nullable();
            $table->string('phone')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};
