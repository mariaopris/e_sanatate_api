<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('diagnostics', function (Blueprint $table) {
            $table->text('predictions')->nullable();
        });
    }


    public function down()
    {
        //
    }
};
