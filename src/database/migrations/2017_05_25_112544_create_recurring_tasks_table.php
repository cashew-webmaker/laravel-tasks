<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recurring_type',255)->nullable()->default(null);
            $table->integer('day_of_week')->nullable()->default(null);
            $table->integer('day_of_month')->nullable()->default(null);
            $table->integer('month_of_year')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->integer('assignor_id')->unsigned()->index();
            $table->integer('assignee_id')->unsigned()->index();
            $table->text('notes')->nullable()->default(null);
            $table->dateTime('assigned_at')->nullable()->default(null);
            $table->dateTime('deadline_at')->nullable()->default(null);
            $table->boolean('auto_review')->nullable()->default(null);
            $table->jsonb('observers')->nullable()->default(null);
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
        Schema::dropIfExists('recurring_tasks');
    }
}
