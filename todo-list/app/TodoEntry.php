<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoEntry extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_COMPLETE = 'complete';

    const PRIORITY_LOWEST = 1;
    const PRIORITY_HIGHEST = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'due_date', 'status',
    ];

    /**
     * Attributes which do not show up in the output data.
     *
     * @var array
     */
    protected $hidden = ['user_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'todo_list_entries';

    /**
     *
     * @return \App\User
     */
    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }


}
