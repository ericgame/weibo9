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
    public function __construct()
    {
        // 10分鐘內只能嘗試3次
        $this->middleware('throttle:3,10', [
            'only' => ['sendResetLinkEmail']
        ]);

        // 1分鐘內只能嘗試2次 (測試)
        // $this->middleware('throttle:2,1', [
        //     'only' => ['showLinkRequestForm']
        // ]);
    }

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

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');
        return view('auth.passwords.reset', compact('token'));
    }

    public function reset(Request $request)
    {
        // 1.驗證數據是否正確
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
        $email = $request->email;
        $token = $request->token;
        $expires = 60 * 10; // 找回密碼鏈接的有效時間 (單位: 秒)

        // 2.獲取對應用戶
        $user = User::where("email", $email)->first();

        // 3.如果不存在
        if (is_null($user)) {
            session()->flash('danger', '此 email 未註冊 !');
            return redirect()->back()->withInput();
        }

        // 4.讀取重置的記錄
        $record = (array) DB::table('password_resets')->where('email', $email)->first();

        // 5.記錄存在
        if ($record) {
            // 5.1.檢查是否過期
            if (Carbon::parse($record['created_at'])->addSeconds($expires)->isPast()) {
                session()->flash('danger', '鏈接已過期，請重新嘗試 !');
                return redirect()->back();
            }

            // 5.2.檢查是否正確
            if (! Hash::check($token, $record['token'])) {
                session()->flash('danger', '令牌錯誤 !');
                return redirect()->back();
            }

            // 5.3.一切正常，更新用戶密碼
            $user->update(['password' => bcrypt($request->password)]);

            // 5.4.提示用戶更新成功
            session()->flash('success', '密碼重置成功，請使用新密碼登入 !');
            return redirect()->route('login');
        }

        // 6.記錄不存在
        session()->flash('danger', '未找到重置記錄 !');
        return redirect()->back();
    }
}
