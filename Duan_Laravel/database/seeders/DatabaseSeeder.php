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

        // User seeding (không thay đổi)
        $groupRoles = ['Admin', 'Editor', 'Reviewer'];
        for ($i = 0; $i < 60; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), 
                'remember_token' => $faker->md5,
                'verify_email' => $faker->boolean,
                'is_active' => $faker->boolean,
                'is_delete' => false,
                'group_role' => $faker->randomElement($groupRoles),
                'last_login_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'last_login_ip' => $faker->ipv4,
            ]);
        }

        // Product seeding (đã điều chỉnh)
        $status = ['Đang bán', 'Hết hàng', 'Ngừng bán'];
        $letters = range('A', 'Z');

        for ($i = 0; $i < 70; $i++) {
            $letterIndex = floor($i / 10);
            $letter = $letters[$letterIndex];
            $number = str_pad(($i % 10) + 1, 10, '0', STR_PAD_LEFT);
            $product_id = $letter . $number;

            Product::create([
                'product_id' => $product_id,
                'name' => $faker->name,
                'price' => $faker->numberBetween(10000, 50000),
                'description' => $faker->text,
                'status' => $faker->randomElement($status),
                'image' => $faker->imageUrl,
            ]);
        }
    }
}