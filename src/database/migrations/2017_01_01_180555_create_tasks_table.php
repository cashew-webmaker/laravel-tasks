<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default(null);
            $table->integer('assignor_id')->unsigned()->index();
            $table->integer('assignee_id')->unsigned()->index();
            $table->string('status')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->dateTime('assigned_at')->nullable()->default(null);
            $table->dateTime('deadline_at')->nullable()->default(null);
            $table->dateTime('finished_at')->nullable()->default(null);
            $table->dateTime('reviewed_at')->nullable()->default(null);
            $table->dateTime('deferred_till')->nullable()->default(null);
            $table->boolean('auto_review')->nullable()->default(null);
            $table->integer('recurring_task_id')->unsigned()->index()->nullable()->default(null);
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
        Schema::dropIfExists('tasks');
    }
}
