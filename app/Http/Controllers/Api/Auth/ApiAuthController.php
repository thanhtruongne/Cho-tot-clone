<?php

namespace App\Http\Controllers\Api\Auth;


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
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiAuthController extends Controller
{

  /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api','jwt.vertify'], ['except' => ['login', 'refresh','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [ 
            'email' => 'required|email',
            'password' => 'required'
        ];
        $messages = [
            'email.required' => 'Email không dược trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không dược trống',
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0] , 'status' => 'error']);
        }


        $credentials = request(['email', 'password']);

        if(!$token = auth('api')->claims(['exp' => \Carbon::now()->addDays(1)])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = \Auth::guard('api')->user();
        if(!$user->refresh_token){
            $refreshToken = $this->checkRefreshTokenAndSignatureKey($user);
            $user->refresh_token = $refreshToken; // lưu refreshtoken
        }
        // dd(end(explode('.',$token)));
        $vertify = explode('.',$token);
        $user->signature_key = end($vertify); // lưu chữ ký của token
        $user->save();
       
        return $this->respondWithToken($token,$user);
    }



     // đăng ký   tự đăng  nhập set token
    public function register(Request $request){
        $rules = [ 
            'email' => 'required|email',
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
        ];
        $messages = [
            'email.required' => 'Email không dược trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không dược trống',
            'firstname.required' => 'Họ không dược trống',
            'lastname.required' => 'Tên không dược trống',
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->all()[0] , 'status' => 'error']);
        }
        $email = $request->email;
        $password = $request->password;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        if(User::whereEmail($email)->exists()){
            return response()->json(['message' => 'Email đã tồn tại' , 'status' => 'error']);
        }
        if(!check_password($password)){
            return response()->json(['message' => 'Mật khẩu phải có viết hoa, thường, số, ký tự đặc biệt ít nhất 8 ký tự' , 'status' => 'error']);
        }

        \DB::table('users')->insert([
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'password' => password_hash($password,PASSWORD_DEFAULT)
        ]);
    
        if (! $token = auth('api')->claims(['exp' => \Carbon::now()->addDays(1)])->attempt(['email' => $email , 'password' => $password ])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = \Auth::guard('api')->user();
        $user->refresh_token =  $this->checkRefreshTokenAndSignatureKey($user); // lưu refreshtoken
        // dd(end(explode('.',$token)));
        $user->last_login = \Carbon::now();
        $vertify = explode('.',$token);
        $user->signature_key = end($vertify); // lưu chữ ký của token
        $user->save();

        return $this->respondWithToken($token,null);


    }

    private function checkRefreshTokenAndSignatureKey($user){
        $agent = new Agent();
           $payload = [
                'sub' => $user->id,
                'exp' => strtotime(\Carbon::now()->addDays(7)),
                'ip' => \request()->ip(),
                'device' => $agent->device()
           ];
           $refreshToken = JWTAuth::getJWTProvider()->encode($payload);
        return $refreshToken;
   
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {   
        $id = auth('api')->id();
        \DB::table('users')->where(function($subquery) use($id){
            $subquery->whereId($id);
            $subquery->whereNotNull('refresh_token');
        })->update([
            'refresh_token' => null,
            'signature_key' => null,
        ]);
    
        auth('api')->logout();
        JWTAuth::invalidate(JWTAuth::parseToken());

        return response()->json(['message' => 'Logout thành công' , 'status' => true] , 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    { 
            $token = request()->bearerToken();
            $tokenDecoded = JWTAuth::getJWTProvider()->decode($token);
            $dataVertify = explode('.',$token);
            $user = User::whereId($tokenDecoded['sub'])->where('signature_key',end($dataVertify))->first();
            if(!$user){
                return response()->json(['message' => 'Token không đúng','status' => false],403);
            }
            auth('api')->invalidate(); // set thêm trg hợp nếu token cũ bị rò rỉ ra ngoài hoặc user biết

            $newToken = auth('api')->login($user);
            $vertify = explode('.',$newToken);
            $user->refresh_token = $this->checkRefreshTokenAndSignatureKey($user);
            $user->signature_key = end($vertify); // lưu chữ ký của token
            $user->save();
            return $this->respondWithToken($newToken);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => \Carbon::now()->addDays(1),
            'status' => true,
            'message' => 'Thành công',
            // 'data'=> $user['id']
        ]);
    }
}
