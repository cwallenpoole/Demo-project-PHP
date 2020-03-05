<?php

namespace App;

class TodoEntry extends \App\Model
{
    const STATUS_NEW = 'New';
    const STATUS_COMPLETE = 'Complete';
    const STATUS_DELETED = 'Deleted';
    const STATUS_IN_PROGRESS = 'In progress';

    const PRIORITY_LOWEST = 1;
    const PRIORITY_HIGHEST = 9;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'due_date', 'status', 'list_id'
    ];

    /**
     * Attributes which do not show up in the output data.
     *
     * @var array
     */
    protected $hidden = ['list_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'todo_list_entries';

    /**
     *
     * @return \App\TodoList
     */
    public function parent() {
        return $this->belongsTo(TodoList::class, 'list_id');
    }

    public function lowestPossiblePriority() {
        return static::PRIORITY_LOWEST;
    }
    public function highestPossiblePriority() {
        return static::PRIORITY_HIGHEST;
    }

    /**
     * For use in views. Gives a list of valid statuses.
     *
     * @return array
     */
    public function getValidStatuses() {
        $consts = (new \ReflectionClass(__CLASS__))->getConstants();


        return array_filter(
            $consts,
            function($name){return strpos($name, 'STATUS_') !== false;},
            ARRAY_FILTER_USE_KEY
        );
    }

}
