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
            return response()->json(['message' => $validator->errors()->all()[0] , 'status' => 'error']);
        }
        $password = $request->post('password');
        $username = $request->post('username');
        $user = User::whereUsername($username)->first(['id', 'username','last_login','status','firstname','lastname','username']);
        if ($user) { //Kiểm tra username tồn tại và không bị khoá
            if ($user->status == 0) {
                $this->sendFailedLoginResponse($request);
                return response()->json(['message' =>  trans('auth.blocked'), 'status' => 'error']);
            }
            elseif(isset($user) && $user->status != 0){ 
                if ($user->login($username,$password)) {
                    $request->session()->put('login_attempts', 0);
                    $agent = new Agent();
                    //lưu lịch sử đăng nhập
                    LoginHistory::setLoginHistoryNotUseShouldQueue($user,request()->ip());
                    //lưu thông tin đăng nhập
                  
                    Visits::saveVisits($user->id,$agent,\Request::userAgent());
                    //lưu thời gian hoạt động
                    UserActivities::createUserActivityDuration($user->id,session()->getId());
                    $targetUrl ='';
                    // lưu lần đầu đăng nhập
                    if(is_null($user->last_login)){
                        $user->last_login = Carbon::now();
                        $user->save();
                    }
                
                      // trở lại url khi thao tác bị hết hạn 401
                    if (session()->has('target_url')) {
                        $targetUrl = session()->get('target_url');
                        session()->forget('target_url');
                        return response()->json(['message' =>  trans('auth.success'), 'status' => 'success','redirect' => $targetUrl]);
                    }
                    // dd($targetUrl);
                    return response()->json(['message' =>  trans('auth.success'), 'status' => 'success','redirect' => route('dashboard')]);
                }
            }
        }
        else {
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

        UserActivities::endUserActivityDuration(\Auth::id(),$sessionId);
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

   

    // public function resetPass(Request $request){
    //     $this->validateRequest([
    //         'username' => 'required',
    //         'email' => 'required',
    //     ], $request, [
    //         'username' => 'username',
    //         'email' => 'email',
    //     ]);
    //     $username = $request->username;
    //     $email = $request->email;

    //     $user = User::where('username', '=', $username)->where('auth', '=', 'manual')->first();
    //     if ($user && $user->id > 2){
    //         // $profile = Profile::where('user_id', '=', $user->id)->where('email', '=', $email)->first();
    //         // if ($profile){
    //         //     $pass_new = Str::random(10);

    //         //     // $check_template_mail = MailTemplate::where('code', '=', 'reset_pass');
    //         //     // if (!$check_template_mail->exists()){
    //         //     //     // $mail_template = new MailTemplate();
    //         //     //     $mail_template->code = 'reset_pass';
    //         //     //     $mail_template->name = 'Lấy lại mật khẩu khi quên';
    //         //     //     $mail_template->title = 'Mail lấy lại mật khẩu';
    //         //     //     $mail_template->content = 'Mật khẩu mới của bạn là: {pass}';
    //         //     //     $mail_template->note = 'Đối tượng nhận: mọi user';
    //         //     //     $mail_template->status = 1;
    //         //     //     $mail_template->save();
    //         //     // }

    //         //     // $automail = new Automail();
    //         //     $automail->template_code = 'reset_pass';
    //         //     $automail->params = [
    //         //         'pass' => $pass_new,
    //         //     ];
    //         //     $automail->users = [$profile->user_id];
    //         //     $automail->object_id = $profile->user_id;
    //         //     $automail->object_type = 'reset_pass';
    //         //     $automail->addToAutomail();

    //         //     $user->password = \Hash::make($pass_new);
    //         //     $user->save();

    //         //     return response()->json([
    //         //         'status' => 'success',
    //         //         'message' => 'Password đã thay đổi. Mời vào mail bạn lấy thông tin',
    //         //         'redirect' => route('login'),
    //         //     ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Email không đúng',
    //                 'redirect' => route('login'),
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Tên đăng nhập không đúng',
    //             'redirect' => route('login'),
    //         ]);
    //     }
    // }
    // protected function changePassFirst(Request $request){
        // $user = User::find(profile()->user_id);
    //     $userView = 'check_change_pass_'.$user->username.'_log_'.$user->id;
    //     if(cache()->has($userView)){
    //         $this->validateRequest([
    //             'password_old' => 'required|min:8|max:32',
    //             'password' => 'required|min:8|max:32',
    //             'repassword' => 'same:password',
    //         // ], $request, Profile::getAttributeName());
    //         $password_old = $request->password_old;

    //         if ($request->password == $password_old){
    //             json_result(['status'=>'error','message'=> trans('auth.new_pass_must_diff_old')]);
    //         }

    //         if (!check_password($request->password)){
    //             json_result(['status'=>'error','message'=>trans('auth.pass_not_format')]);
    //         }

    //         // $user = User::find(profile()->user_id);
    //         // $userView = 'check_change_pass_'.$user->username.'_log_'.$user->id;
    //         // //nếu user ko đến hạn đổi mk thì out
    //         // if(!cache()->has($userView))
    //         //           abort(404);

    //         if ($user){
    //             $hash = $user->password;
    //             if (password_verify($password_old, $hash)) {

    //                 $user->password = password_hash($request->password, PASSWORD_DEFAULT);
    //                 $user->last_login = null;

    //                 $user->save();
    //                 cache::forget($userView);

    //                 $this->guard()->logout();
    //                 $request->session()->invalidate();

    //                 return response()->json([
    //                     'status' => 'success',
    //                     'message' => trans('laprofile.change_pass_success'),
    //                     'redirect' => route('login'),
    //                 ]);
    //             }else{
    //                 json_result(['status'=>'error','message'=> trans('auth.old_pass_not_true')]);
    //             }
    //         }
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => trans('auth.account_not_exists'),
    //             'redirect' => route('login'),
    //         ]);
    //     }
    //     else{
    //         $this->guard()->logout();
    //         $request->session()->invalidate();
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => trans('auth.out_of_time_user_request'),
    //             'redirect' => route('login'),
    //         ]);
    //     }


    // }




// }
}
