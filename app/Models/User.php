<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    //https://spatie.be/docs/laravel-permission/v6/installation-laravel
    ////https://filamentphp.com/docs/3.x/panels/resources/editing-records

    const ROLE_ADMIN = 'Admin';
    const ROLE_EDITOR = 'Writer';
    const ROLE_USER = 'User';
    const ROLE_DEFAULT = self::ROLE_USER;

    public function canAccessPanel(Panel $panel): bool {
        // return $this->hasRole(self::ROLE_ADMIN)
        //         || $this->hasRole(self::ROLE_EDITOR);

        return true;
    }

    public function isAdmin(){
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isEditor(){
        return $this->hasRole(self::ROLE_EDITOR);
    }

    public function isUser(){
        return $this->hasRole(self::ROLE_USER);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'color',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(){
        return $this->belongsToMany(Post::class, 'post_user')
                    ->withPivot(['order'])            
                    ->withTimestamps();
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

 

}
