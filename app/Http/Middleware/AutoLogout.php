<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Session;
class AutoLogout
{
    protected $timeout = 10080; //set trong 15 phút
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('lastActivityTime')) {
            Session::put('lastActivityTime', time());
        } elseif (time() - Session::get('lastActivityTime') > $this->timeout) {
            Session::forget('lastActivityTime');
            \Auth::logout();
            if ($request->ajax())
                return response()->json(['message' => 'Session expired'], 419);
            return redirect(route('login'))->withErrors(['Bạn đã không thao tác trong 15 phút']);
        }
        Session::put('lastActivityTime', time());//f5 browser
        return $next($request);
    }
}
