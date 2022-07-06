<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'email' => env('ADMIN_EMAIL'),
            'firstname' => 'Sam',
            'lastname' => 'DB',
            'password' => env('DEFAULT_PASSWORD')
        ]);

        $user = User::create([
            'email' => env('USER_EMAIL'),
            'firstname' => 'Sam',
            'lastname' => 'DB',
            'password' => env('DEFAULT_PASSWORD')
        ]);

        $productOne = Product::create([
            'name' => 'table',
            'price' => 200,
            'description' => 'A nice table.',
            'admin_id' => $admin->id,
            'slug' => Str::slug('table')
        ]);

        $productTwo = Product::create([
            'name' => 'chair',
            'price' => 100,
            'description' => 'To sit at.',
            'admin_id' => $admin->id,
            'slug' => Str::slug('chair')
        ]);

        $productThree = Product::create([
            'name' => 'closet',
            'price' => 150,
            'description' => 'A closet is an enclosed space, with a door, used for storage, particularly that of clothes.',
            'admin_id' => $admin->id,
            'slug' => Str::slug('closet')
        ]);

        $productFour = Product::create([
            'name' => 'sofa',
            'price' => 175,
            'description' => 'Sofa so good.',
            'admin_id' => $admin->id,
            'slug' => Str::slug('sofa')
        ]);
    }
}
