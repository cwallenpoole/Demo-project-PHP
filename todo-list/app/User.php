<?php

namespace App;

use \Illuminate\Support\Str;
use \Illuminate\Contracts\Auth\MustVerifyEmail;
use \Illuminate\Foundation\Auth\User as Authenticatable;
use \Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * A simpler way to look up a user by email address
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $emailAddress
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmailIs($query, $emailAddress)
    {
        return $query->where('email', '=', $emailAddress);
    }


    /**
     * A simpler way to look up a user by token
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param string $token
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTokenIs($query, $token)
    {
        return $query->where('api_token', '=', $token);
    }

    /**
     * This simply makes it easier to create a token inline, thereby allowing
     * `$user->addToken()->save()` or similar.
     *
     * @return \App\User
     */
    public function addToken() {
        if((time() - $this->getTokenTime()) > 300) {
            // The api_token is a combination of random content and the time.
            // This is a cheap way of letting us force the token to expire.
            $this->attributes['api_token'] = base64_encode(Str::random(49) . ':' . time());
            $this->saveIfNotDirty();
        }
        return $this;
    }

    /**
     * Creates token and returns it.
     *
     * @return string
     */
    public function getApiTokenAttribute() {
        $this->addToken();

        return $this->attributes['api_token'];
    }

    /**
     * Allows for outside classes to clear the API token from the model. Because of the very specific
     * nature of the API token and because this is an explicit call, putting it in the middle of a
     * broader save structure shouldn't be counter-intuitive.
     *
     * @return \App\User
     */
    public function cleadApiToken() {
        $this->attributes['api_token'] = null;
        $this->saveIfNotDirty();
        return $this;
    }


    /**
     * This is here as a guard. We don't want to allow an outside classes to modify the api token.
     *
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function setApiTokenAttribute($value) {
        throw new \InvalidArgumentException(
            'The API token cannot be set directly. '.
            'You must used `clearApiToken` or allow the object to generate its own'
        );
    }


    /**
     * Returns the time portion of the User's token.
     *
     * @return number
     */
    protected function getTokenTime() {
        $token = base64_decode($this->attributes['api_token']);
        if(strpos($token, ':') === false) {
            return 0;
        }
        list($_, $ret) = explode(':', $token);

        return (int) $ret;
    }

    /**
     * When setting the API token we could be in a situation where the user object is updated
     * but is not ready to be saved yet. We don't want to make the developers worry about
     * the order they instantiate or update. Therefore this will automatically save iff
     * no other changes are made.
     */
    protected function saveIfNotDirty() {
        if(!$this->getDirty()){
            $this->save();
        }
    }
}
