<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        // 獲取去除掉 ID為1 的所有用戶 ID 數組
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        // 1號用戶 關注除了 1號用戶 以外的所有用戶
        $user->follow($follower_ids);

        // 除了 1號用戶 以外的所有用戶都來關注 1號用戶
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
