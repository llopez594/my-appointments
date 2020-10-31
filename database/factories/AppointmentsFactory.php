<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;

use App\Appointment;
use App\User;

$factory->define(Appointment::class, function (Faker $faker) {
    $doctorIds = User::doctors()->pluck('id');
    $patientIds = User::patients()->pluck('id');

    $date = $faker->dateTimeBetween('-1 years', 'now');
    $schedule_date = $date->format('Y-m-d');
    $scheduled_time = $date->format('H:i:s');

    $types = ['Consulta', 'Examen', 'Operacion'];
    $statuses = ['Atendida', 'Cancelada'];

    return [
        'description' => $faker->sentence(5),
        'specialty_id' => $faker->numberBetween(1, 3),
        'doctor_id' => $faker->randomElement($doctorIds),
        'patient_id' => $faker->randomElement($patientIds),
        'schedule_date' => $schedule_date,
        'scheduled_time' => $scheduled_time,
        'type' => $faker->randomElement($types),
        'status' => $faker->randomElement($statuses)
    ];
});
