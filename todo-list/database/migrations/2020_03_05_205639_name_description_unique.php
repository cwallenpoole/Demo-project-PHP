<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TodoList;

class NameDescriptionUnique extends Migration
{
    private $unique_name = 'users_lists_must_be_uniquely_named';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(TodoList::getClassTable(), function (Blueprint $table) {
            $table->unique(['user_id', 'description'], $this->unique_name);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(TodoList::getClassTable(), function (Blueprint $table) {
            $table->dropUnique($this->unique_name);
        });
    }
}
