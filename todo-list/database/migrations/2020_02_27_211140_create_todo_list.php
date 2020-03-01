<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TodoEntry;

class CreateTodoList extends Migration
{
    /**
     * Run the migrations. Creates the table which holds the Todo list entries.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_list_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('description');
            $table->dateTime('due_date');
            $table->integer('priority')
                ->default(TodoEntry::PRIORITY_LOWEST);
            $table->string('status')
                ->default(TodoEntry::STATUS_NEW);
            $table->timestamps();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo_list_entries');
    }
}
