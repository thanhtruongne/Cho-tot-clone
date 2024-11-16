<?php

namespace App\Http\Controllers\Auth;


use App\Models\LoginHistory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\UserActivities;
use App\Models\Visits;
use Carbon\Carbon;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $rules = [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
        $messages = [
            'password.required' => trans('auth.password_not_blank'),
            'username.required' => trans('auth.username_not_blank'),
        ];


        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0], 'status' => 'error']);
        }
        $password = $request->post('password');
        $username = $request->post('username');
        $user = User::whereUsername($username)->first(['id', 'username', 'last_login', 'status', 'firstname', 'lastname', 'username']);
        if ($user) { //Kiểm tra username tồn tại và không bị khoá
            if ($user->status == 0) {
                $this->sendFailedLoginResponse($request);
                return response()->json(['message' =>  trans('auth.blocked'), 'status' => 'error']);
            } elseif (isset($user) && $user->status != 0) {
                if ($user->login($username, $password)) {
                    $request->session()->put('login_attempts', 0);
                    $agent = new Agent();
                    //lưu lịch sử đăng nhập
                    LoginHistory::setLoginHistoryNotUseShouldQueue($user, request()->ip());
                    //lưu thông tin đăng nhập

                    Visits::saveVisits($user->id, $agent, \Request::userAgent());
                    //lưu thời gian hoạt động
                    UserActivities::createUserActivityDuration($user->id, session()->getId());
                    $targetUrl = '';
                    // lưu lần đầu đăng nhập
                    if (is_null($user->last_login)) {
                        $user->last_login = Carbon::now();
                        $user->save();
                    }

                    // trở lại url khi thao tác bị hết hạn 401
                    if (session()->has('target_url')) {
                        $targetUrl = session()->get('target_url');
                        session()->forget('target_url');
                        return response()->json(['message' =>  trans('auth.success'), 'status' => 'success', 'redirect' => $targetUrl]);
                    }
                    // dd($targetUrl);
                    return response()->json(['message' =>  trans('auth.success'), 'status' => 'success', 'redirect' => route('dashboard')]);
                }
            }
        } else {
            $this->sendFailedLoginResponse($request);
            return response()->json(['message' =>  trans('auth.vertify_fail'), 'status' => 'error']);
        }
    }

    public function logout(Request $request)
    {
        $model = LoginHistory::where('user_id', '=', \Auth::id())->orderBy('created_at', 'DESC')->first();
        if ($model) {
            $model->updated_at = time();
            $model->save();
        }
        $sessionId = session()->getId();
        session()->flush();
        $this->guard()->logout();
        $request->session()->invalidate();

        UserActivities::endUserActivityDuration(\Auth::id(), $sessionId);
        return  redirect(route('login'));
    }


    public function showLoginForm()
    {
        if (\auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('pages.auth.login');
    }
    public function sendFailedLoginResponse(Request $request)
    {
        $attempts = 0;
        if ($request->session()->has('login_attempts')) {
            $attempts = $request->session()->get('login_attempts');
            $request->session()->put('login_attempts', $attempts + 1);
        }
        $request->session()->put('login_attempts', $attempts + 1);
    }

    public function manageUsers()
    {
        $data = User::all();
        return view('pages.auth.manageUsers', compact('data'));
    }


    public function manageUsersAdd(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->password = bcrypt($request->password);
        $user->save();

        // Trả về thông báo thành công
        return redirect()->route('manage-users')->with('success', 'Người dùng đã được thêm thành công!');
    }

    public function manageUsersDelete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('manage-users')->with('success', '');
    }

    public function manageUsersEdit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('manage-users')->with('error', 'Không tìm thấy người dùng!');
        }

        return view('pages.auth.manageUsersEdit', compact('user')); // Chuyển dữ liệu user vào view
    }

    public function manageUsersUpdate(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
        ]);

        $user = User::find($id);

        if (!$user) {
            return redirect()->route('manage-users')->with('error', 'Không tìm thấy người dùng!');
        }

        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('manage-users')->with('success', 'Người dùng đã được cập nhật thành công!');
    }
}
