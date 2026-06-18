<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;
class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'name' => 'Demo',
            'email' => 'demo@gmail.com',
            'phone' => '03422754409',
            'bussiness_name' => 'Demo',
            'bussiness_name' => 'Demo',
            'password' => bcrypt('12345678'),
        ]);
    }
}
