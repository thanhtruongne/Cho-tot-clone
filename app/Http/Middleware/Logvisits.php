<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class Logvisits
{
    public function handle($request, Closure $next,string $guard)
    {
      
        if(auth( $guard)->check()) {
            $agent = new Agent();
            $id = auth($guard)->id();
            $ids = [];
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
                $ids[] = $id;

                
                \Cache::put('online-users', $users, \Config::get('session.lifetime'));
                \Cache::put('online-users-id', $ids, \Config::get('session.lifetime'));
            }
        }
        return $next($request);
    }
}