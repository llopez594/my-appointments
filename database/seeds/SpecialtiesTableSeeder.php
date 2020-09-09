<?php

use Illuminate\Database\Seeder;
use App\Specialty;
use App\User;

class SpecialtiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialties = [
            'Oftanmologia',
            'Pediatria',
            'Neurologia',
        ];
        foreach ($specialties as $specialtyName){
            $specialty = Specialty::create([
                'name' => $specialtyName
            ]);
            $specialty->users()->saveMany(
                factory(User::class, 3)->state('doctor')->make()
            );
        }

        User::find(3)->specialties()->save($specialty);

    }
}
