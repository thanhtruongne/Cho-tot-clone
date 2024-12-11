<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('forgot-password');
    }




    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

$resetLink = url('http://localhost:5173/confirm_reset_password/' . $user->id);
 // Sử dụng ID của người dùng làm phần của URL
        Mail::send('emails.test', ['name' => $user->firstname.' ' .$user->lastname, 'resetLink' => $resetLink], function ($message) use ($email) {
            $message->to($email)->subject('Reset Password');
        });

        return response()->json(['message' => 'Password reset link has been sent!'], 200);
    }


    public function showResetForm($userId)
    {
        // Kiểm tra người dùng tồn tại không
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return view('reset-password', ['user' => $user]);
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::find($id);
        // dd($user) or die();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password has been reset successfully'], 200);
    }

}
