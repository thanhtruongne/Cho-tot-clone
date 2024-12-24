<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // if($user){
            //     $dataKeyVertify = explode('.',request()->bearerToken());
            //     if(!in_array($user->signature_key,$dataKeyVertify)){
            //         return response()->json(['status' => 'Token không đúng'],403);
            //     }
            // }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token không đúng'],403);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token đã hết hạn'],401);
            }
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException){
                return response()->json(['status' => 'Token is Blacklisted'], 400);
		    }else{
                return response()->json(['status' => 'Authorization Token not found'],404);
            }
        }
        return $next($request);
    }
    
}