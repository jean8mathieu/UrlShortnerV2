<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\Hash;
use App\User;
use \Illuminate\Support\Facades\Log;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            User::create([
                'username' => '',
                'password' => Hash::make(''),
                'email' => '',
            ]);
        } catch (\Exception $e) {
            Log::error('An error happen while running the seeder: ' . $e->getMessage());
        }
    }
}
