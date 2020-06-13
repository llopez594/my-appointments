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
        // 1
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

        // 2
        User::create([
            'name' => 'Paciente Test',
            'email' => 'patient@example.com',
            'password' => Hash::make('1234'),
            'role' => 'patient'
        ]);

        // 3
        User::create([
            'name' => 'Médico Test',
            'email' => 'doctor@example.com',
            'password' => Hash::make('1234'),
            'role' => 'doctor'
        ]);
        factory(User::class, 50)->create();
    }
}
