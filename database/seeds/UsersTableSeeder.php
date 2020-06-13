<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Luis Lopez',
            'email' => 'luis12l@hotmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin.1234'),
            'dni' => '24847594',
            'address' => 'Puerto Ordaz',
            'phone' => '+58 4249494302',
            'role' => 'admin'
        ]);

        factory(User::class, 50)->create();
    }
}
