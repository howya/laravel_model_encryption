<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('encrypt_string')->nullable();
            $table->string('encrypt_string_bi', 64)->nullable();
            $table->text('encrypt_integer')->nullable();
            $table->string('encrypt_integer_bi', 64)->nullable();
            $table->text('encrypt_boolean')->nullable();
            $table->string('encrypt_boolean_bi', 64)->nullable();
            $table->text('encrypt_another_boolean')->nullable();
            $table->text('encrypt_float')->nullable();
            $table->string('encrypt_float_bi', 64)->nullable();
            $table->text('encrypt_date')->nullable();
            $table->string('encrypt_date_bi', 64)->nullable();
            $table->string('hash_string', 64)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_tables');
    }
}
