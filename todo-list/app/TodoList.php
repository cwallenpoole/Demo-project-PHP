<?php

namespace App;

class TodoList extends \App\Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'user_id', 'is_private'
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
    protected $table = 'todo_lists';

    /**
     *
     * @return \App\User
     */
    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Allows us to reference the entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries() {
        return $this->hasMany(TodoEntry::class, 'list_id');
    }

}
