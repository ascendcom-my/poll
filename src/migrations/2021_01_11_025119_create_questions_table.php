<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('type');
            $table->boolean('is_ballot');
            $table->boolean('allow_abstain');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('stop_at')->nullable();
            $table->boolean('allow_reveal_result')->default(0);
            $table->integer('min');
            $table->integer('max');
            $table->uuid('token')->unique()->index();
            $table->tinyInteger('sequence');
            $table->string('group_id')->nullable()->index();
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
        Schema::dropIfExists('questions');
    }
}
