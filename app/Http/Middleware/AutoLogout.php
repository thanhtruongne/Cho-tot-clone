<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Session;
use App\Models\UserActivities;
use App\Models\LoginHistory;
use App\Models\User;
class AutoLogout
{
    protected $timeout = 10080; //set trong 15 phút
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('lastActivityTime')) {
            Session::put('lastActivityTime', time());
        } elseif (time() - Session::get('lastActivityTime') > $this->timeout) {
            Session::forget('lastActivityTime');
            $this->logoutDebug($request);
            if ($request->ajax())
                return response()->json(['message' => 'Session expired'], 419);
            return redirect(route('login'))->withErrors(['Bạn đã không thao tác trong 15 phút']);
        }
        Session::put('lastActivityTime', time());//f5 browser
        return $next($request);
    }

    private function logoutDebug(Request $request){
        $id = auth('web')->id();
        $model = LoginHistory::where('user_id', '=', $id)->orderBy('created_at', 'DESC')->first();
        if ($model) {
            $model->updated_at = time();
            $model->save();
        }
        $sessionId = session()->getId();

        UserActivities::endUserActivityDuration($id,$sessionId);
        session()->flush();
        auth('web')->logout();
        $request->session()->invalidate();
        \Artisan::call('modelCache:clear', ['--model' => User::class]);
    }
}
