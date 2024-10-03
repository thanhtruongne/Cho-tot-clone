<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use Cachable;

    protected $table = 'login_history';
    protected $table_name = "Lịch sử đăng nhập";
    protected $fillable = [
       'user_id',
       'user_code',
       'user_name',
       'number_hits',
       'ip_address',
       'user_type',
    ];

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    

    public static function setLoginHistoryNotUseShouldQueue($profile, $ip=null) {
      
        $user = LoginHistory::where('user_id', '=', $profile->id)
            ->orderBy('created_at', 'DESC')
            ->first(['number_hits']);
        // dd($user);
        $model = new LoginHistory();
        $model->user_id = $user->id ?? $profile->id;
        $model->user_code = $user->username ??  $profile->username;;
        $model->user_name = !empty($user) ?  $user->lastname . ' ' . $user->firstname : $profile->lastname . ' ' . $profile->firstname ;
        $model->ip_address =$ip;
        if ($user){
            $model->number_hits = $user->number_hits + 1;
        }else{
            $model->number_hits = 1;
        }
        $model->save();
    }

    public static function getLoginHistoryByYear($user_id){
        $user_login = LoginHistory::where('user_id', '=', $user_id)
            ->where(\DB::raw('year(created_at)'), '=', date('Y'))
            ->count();

        return $user_login;
    }

}
