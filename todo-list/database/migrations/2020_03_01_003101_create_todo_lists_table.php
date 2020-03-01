<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TodoList;
use App\TodoEntry;
use Illuminate\Support\Facades\DB;
use App\User;

class CreateTodoListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('description');
            $table->boolean('is_private')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('todo_list_entries', function(Blueprint $table) {
            $table->unsignedBigInteger('list_id');

            $table->foreign('list_id')
                ->references('id')
                ->on('todo_lists')
                ->onDelete('cascade');
        });

        // Previously we had the map User -> entry. We now need to assign all of those
        // entries into an actual list. Creating default lists for all existing users
        foreach (User::find() as $user) {
            $list = (new TodoList());
            $list->fill([
                'description' => $user->name . '\'s list',
                'user_id' => $user->id
            ])->save();

            TodoEntry::where('user_id', '=', $user->id)
                ->update(['list_id' => $list->id]);
        }

        Schema::table('todo_list_entries', function(Blueprint $table) {
            $table->removeColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todo_list_entries', function(Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            DB::beginTransaction();
            foreach(TodoList::find() as $list) {
                TodoEntry::where('list_id', '=', $list->id)
                    ->update(['user_id' => $list->owner->id]);
            }

            $table->dropColumn('list_id');
            DB::commit();
        });

        Schema::dropIfExists('todo_lists');
    }
}
