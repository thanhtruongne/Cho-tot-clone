<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Jenssegers\Agent\Agent;

class Visits extends Model
{
    use Cachable;

    protected $table = 'visits';
    protected $fillable = [
        'method',
        'url',
        'referer',
        'useragent',
        'device',
        'platform',
        'browser',
        'ip',
        'visitor_id',
        'device_type',
        'device_cate',
    ];

 
    public static function saveVisits($user_id, Agent $agent, $userAgent )
    {
//        $agent = new Agent();
//        \Log::info($agent->browser());
        $visits = new Visits();
        $visits->method = \Request::getMethod();
        $visits->url = \Request::fullUrl();
        $visits->referer = $_SERVER['HTTP_REFERER'] ?? null;
        $visits->useragent = $userAgent ?? '';
        $visits->device = $agent->device();
        // $visits->device_type = $agent->deviceType();
        $visits->device_cate = self::getDeviceCate($agent);
        $visits->platform = $agent->platform();
        $visits->browser = $agent->browser();
        $visits->ip = \Request::ip();
        $visits->visitor_id = $user_id ?? null;
        $visits->save();
    }

    private static function getDeviceCate($agent){
//        $agent = new Agent();
        if($agent->isTablet())
            return 'tablet';
        elseif($agent->isMobile())
            return 'mobile';
        elseif($agent->isDesktop())
            return 'desktop';
        return 'desktop';
    }

}
