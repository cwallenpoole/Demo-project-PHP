<?php


use \Illuminate\Support\Facades\Artisan;
use \Illuminate\Foundation\Inspiring;
use App\User;
use App\TodoEntry;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('create:user {--name=} {--email=} {--password=}', function ($name, $email, $password) {
    $vars = array_filter(get_defined_vars());
    $user = (User::EmailIs($email)->first() ?: new User())
        ->fill($vars);
    $user->save();

    $this->comment(json_encode(
        $user->toArray(),
        128)
    );
})->describe('Creates a new user');

Artisan::command(
    'create:todo-item {--owner=} {--description=} {--due_date=}',
    function ($owner, $description, $due_date) {
        $owner = User::EmailIs($owner)->first();

        if(!$owner) {
            throw new \BadMethodCallException('Owner not found ' . $owner);
        }
        $todo = new TodoEntry();
        $todo->fill(['description' => $description, 'due_date' => $due_date]);
        $owner->entries()->save($todo);
        $this->comment(json_encode($todo->toArray(), 128));
    }
)->describe('Creates a new Todo entry');


Artisan::command(
    'update:todo-item {--id=} {--owner=} {--description=} {--due_date=} {--priority=} {--status=}',
    function ($id, $priority='', $owner='', $description='', $due_date='', $status='') {
        if(!$id) {
            throw new \BadMethodCallException('ID is required');
        }
        $todo = TodoEntry::findOrFail($id);

        $ownerObject = $owner? User::EmailIs($owner)->first(): $todo->owner;
        if(!$ownerObject) {
            throw new \BadMethodCallException('Owner not found ' . $owner);
        }

        $vars = array_filter(get_defined_vars());
        unset($vars['id']);
        unset($vars['owner']);
        unset($vars['ownerObject']);

        if($vars) {
            $todo->fill($vars);
            $todo->save();
            if($owner) {
                $ownerObject->entries()->save($todo);
            }
        }
        $output = $todo->toArray() + ['owner' => $todo->owner->toArray()];
        $this->comment(json_encode($output, 128));
    }
)->describe('Creates a new Todo entry');
