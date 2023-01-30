<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // 1.驗證郵箱
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // 2.獲取對應用戶
        $user = User::where("email", $email)->first();

        // 3.如果不存在
        if (is_null($user)) {
            session()->flash('danger', 'email未註冊');
            return redirect()->back()->withInput();
        }

        // 4.生成 Token，會在視圖 emails.reset_link 裡拼接鏈接
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        // 5.入庫，使用 updateOrInsert 來保持 Email 唯一
        DB::table('password_resets')->updateOrInsert(['email' => $email], [
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => new Carbon,
        ]);

        // 6.將 Token 鏈接發送給用戶
        Mail::send('emails.reset_link', compact('token'), function ($message) use ($email) {
            $message->to($email)->subject('忘記密碼');
        });

        session()->flash('success', '重置郵件發送成功，請查收 !');
        return redirect()->back();
    }
}
