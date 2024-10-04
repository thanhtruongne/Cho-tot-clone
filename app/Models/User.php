<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Cachable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'last_login',
        'avatar',
        'email',
        'dob',
        'address',
        'district_code',
        'province_code',
        'ward_code',
        'identity_card',
        'date_range',
        'issued_by',
        'gender',
        'signing_create_account',
        'phone',
        'status',
        'created_by',
        'updated_by',
        'type_user',
        'star',
        'category_like',
        'content',
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


    public function login($username,$password,$remember = false){
        $auth = \Auth::attempt([
            'username' => $this->username,
            'password' => $password
        ], $remember);

        if ($auth) {
            return true;
        }
    }
}
