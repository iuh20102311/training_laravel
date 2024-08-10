<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // User seeding
        $groupRoles = ['Admin', 'Editor', 'Reviewer'];
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'remember_token' => $faker->md5,
                'verify_email' => $faker->boolean ? $faker->md5 : null,
                'is_active' => $faker->boolean,
                'is_delete' => false,
                'group_role' => $faker->randomElement($groupRoles),
                'last_login_at' => $faker->dateTimeThisYear(),
                'last_login_ip' => $faker->ipv4,
            ]);

            // Create 1-3 addresses for each user
            for ($j = 0; $j < $faker->numberBetween(1, 3); $j++) {
                UserAddress::create([
                    'user_id' => $user->id,
                    'phone_number' => $faker->phoneNumber,
                    'city' => $faker->city,
                    'district' => $faker->word,
                    'ward' => $faker->word,
                    'address' => $faker->address,
                    'is_default' => $j == 0, // First address is default
                ]);
            }
        }

        // Product seeding
        $status = ['Đang bán', 'Hết hàng', 'Ngừng bán'];
        $letters = range('A', 'Z');

        for ($i = 0; $i < 100; $i++) {
            $letterIndex = floor($i / 10);
            $letter = $letters[$letterIndex];
            $number = str_pad(($i % 10) + 1, 10, '0', STR_PAD_LEFT);
            $product_id = $letter . $number;

            Product::create([
                'product_id' => $product_id,
                'name' => $faker->unique()->word,
                'price' => $faker->randomFloat(2, 10000, 50000),
                'description' => $faker->sentence,
                'status' => $faker->randomElement($status),
                'image' => $faker->imageUrl(),
            ]);
        }

        // DiscountCode seeding
        for ($i = 0; $i < 20; $i++) {
            // Randomly choose between amount or percentage
            $discountType = $faker->randomElement(['amount', 'percentage']);

            // Set amount and percentage based on the chosen discount type
            $amount = $discountType === 'amount' ? $faker->randomFloat(2, 10000, 50000) : null;
            $percentage = $discountType === 'percentage' ? $faker->randomFloat(2, 10, 30) : null;

            DiscountCode::create([
                'code' => $faker->unique()->word,
                'amount' => $amount,
                'percentage' => $percentage,
                'valid_from' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'valid_to' => $faker->dateTimeBetween('+1 month', '+2 months'),
                'usage_limit' => $faker->numberBetween(10, 100),
                'used_time' => $faker->numberBetween(0, 10),
                'is_active' => $faker->boolean,
            ]);
        }

        // Order and related tables seeding
        for ($i = 0; $i < 50; $i++) {
            $discountCode = $faker->boolean ? DiscountCode::inRandomOrder()->first() : null;
            $user = User::inRandomOrder()->first();

            $order = Order::create([
                'discount_code_id' => $discountCode ? $discountCode->id : null,
                'user_id' => $user->id,
                'order_number' => $faker->unique()->numerify('ORD-######'),
                'user_name' => $user->name,
                'user_email' => $user->email,
                'phone_number' => $faker->phoneNumber,
                'order_date' => $faker->dateTimeThisYear(),
                'order_status' => $faker->randomElement([0, 1, 2]),
                'payment_method' => $faker->randomElement([1, 2]),
                'shipment_date' => $faker->optional()->dateTimeThisMonth(),
                'cancel_date' => $faker->optional()->dateTimeThisMonth(),
                'sub_total' => $faker->randomFloat(2, 10000, 100000),
                'total' => $faker->randomFloat(2, 10000, 100000),
                'tax' => $faker->randomFloat(2, 100, 1000),
                'discount_amount' => $discountCode ? $faker->randomFloat(2, 0, 1000) : 0,
                'discount_code' => $discountCode ? $discountCode->code : null,
            ]);

            // Create 1 to 5 order details for each order
            for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                $product = Product::inRandomOrder()->first();

                // Create a shipping address for this order detail
                $shippingAddress = ShippingAddress::create([
                    'order_id' => $order->order_id,
                    'phone_number' => $faker->phoneNumber,
                    'city' => $faker->city,
                    'district' => $faker->word,
                    'ward' => $faker->word,
                    'address' => $faker->address,
                    'ship_charge' => $faker->randomFloat(2, 1000, 5000),
                ]);

                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'shipping_address_id' => $shippingAddress->id,
                    'product_name' => $product->name,
                    'price_buy' => $product->price,
                    'quantity' => $faker->numberBetween(1, 5),
                ]);
            }
        }
    }
}