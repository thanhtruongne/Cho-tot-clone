<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UserActivities extends Model
{

    protected $table = 'user_activity_durations';
    protected $table_name = "thời gian hoạt động của user";
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'session_id',
        'start_time',
        'end_time',
        'last_acti_time',
    ];

    public static function createUserActivityDuration($userId,$sessionId){
        $current = now()->timestamp;
        DB::table('user_activity_durations')->insert([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'start_time' => $current,
            'last_acti_time' => $current,
        ]);
    }

    public static function endUserActivityDuration($userId,$sessionId){
        $current = now()->timestamp;
        DB::table('user_activity_durations')->where(['user_id' => $userId, 'session_id' => $sessionId])->update([
            'end_time' => $current,
            'last_acti_time' => $current,
        ]);
    }
}
