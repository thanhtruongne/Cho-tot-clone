<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class LogVisits
{
    public function handle($request, Closure $next,string $guard)
    {
        if(auth(guard: 'web')->check()) {
        $agent = new Agent();
        $id = auth('web')->id();
        $users = \Cache::get('online-users');
            if(empty($users)) {
                \Cache::put('online-users', [['id' => $id, 'last_activity_at' => now(), 'ip_address' => request()->ip(), 'device' => $agent->device(), 'platform' => $agent->platform(), 'browser' => $agent->browser()]], \Config::get('session.lifetime'));
            } else {
             
                foreach ($users as $key => $user) {
                    if($user['id'] === $id) {
                        unset($users[$key]);
                        continue;
                    }

                    if ($user['last_activity_at'] < now()->subMinutes(10)) {
                        unset($users[$key]);
                        continue;
                    }
                }
                
                $users[] = ['id' => $id, 'last_activity_at' => now(), 'ip_address' => request()->ip(), 'device' => $agent->device(), 'platform' => $agent->platform(), 'browser' => $agent->browser()];

                
                \Cache::put('online-users', $users, \Config::get('session.lifetime'));
            }
        }
        return $next($request);
    }
}