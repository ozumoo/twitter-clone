<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getAvatarAttribute($value)
    {
        $path = 'storage/' . $value ;
        return asset(  $value ? $path : 'images/default-avatar.jpeg');
    }

    public function timeline()
    {
        $friends = $this->follows()->pluck('id');
        return Tweet::whereIn('user_id', $friends)
                    ->orWhere('user_id', $this->id)
                    ->withCount(['likes', 'dislikes'])
                    ->latest()->paginate(50);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class)
            ->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function path($append = '')
    {
        $path = route('profile' , $this->username);

        return $append ? "{$path}/{$append}" : $path;
    }

}
