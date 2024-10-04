<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cache;
class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!\Auth::check()) {
            if ($request->ajax()){
                if(!session()->has('target_url')){
                    $refererUrl = $request->header('Referer');
                    session()->put('target_url', $refererUrl);
                }

                return response()->json(["message", "Authentication Required!"],401);
            }
            if(!session()->has('target_url')){
                session()->put('target_url', $request->fullUrl());
            }
            return  redirect(route('login'));
        }

        if (\Auth::check()){
            $userId = \auth()->id();
            if (!session()->get('profile')) {
                $profile = User::whereId($userId)->disableCache()->first();
                $group_permission = \Auth::user()->roles()->first()->code;
                session(['group_permission' => $group_permission]);
                session(['profile' => $profile]);
                session()->save();
            }

            if(!cache('avatar_'.profile()->user_id)){
                $avatar = User::whereId(\profile()->user_id)->value('avatar');
                Cache::forever('avatar_'. \profile()->user_id, $avatar ?? '');
            }
        }
        return $next($request);
    }
}
