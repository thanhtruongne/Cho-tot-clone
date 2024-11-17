<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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
        $auth = auth('web')->attempt([
            'username' => $this->username,
            'password' => $password
        ], $remember);

        if ($auth) {
            return true;
        }
    }

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function product_rent_house(){
        return $this->hasMany(ProductRentHouse::class,'user_id','id');
    }

    public function isAdmin() {
        $cacheKey = 'admin_access_for_' . auth('web')->id();
        return Cache::rememberForever($cacheKey, function () {
            if (in_array(auth('web')->user()->username, ['admin', 'superadmin'])) {
                return true;
            }
            return false;
        });
    }

}
