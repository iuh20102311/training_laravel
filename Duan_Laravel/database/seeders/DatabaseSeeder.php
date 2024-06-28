<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Tạo một mảng các group_role có thể có
        $groupRoles = ['Admin', 'Editor', 'Reviewer'];

        // Tạo 20 user
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Mật khẩu mặc định là 'password'
                'remember_token' => $faker->md5,
                'verify_email' => $faker->boolean,
                'is_active' => $faker->boolean,
                'is_delete' => false, // Mặc định là false vì đây là user mới
                'group_role' => $faker->randomElement($groupRoles),
                'last_login_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'last_login_ip' => $faker->ipv4,
            ]);
        }

        $status = ['Đang bán', 'Hết hàng', 'Ngừng bán'];

        for ($i = 0; $i < 20; $i++) {
            Product::create([
                'name' => $faker->name,
                'price'=> $faker->numberBetween($int1 = 10000,$int2= 50000),
                'description' => $faker->text,
                'status' => $faker->randomElement($status),
                'image' => $faker->imageUrl,
            ]);
        }
    }
}