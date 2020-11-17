<?php

namespace App\Console\Commands;

use App\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar mensajes via FCM';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Buscando citas medicas:');
        // 01 December -> 02 December (No: 03 December)
        // 3pm         -> 3pm

        //hora actual
        //2018-12-01 14:00:00
        $now = Carbon::now();

        //schedule_date 2018-12-02
        //scheduled_time 15:00:00

        $headers = ['id', 'schedule_date', 'scheduled_time', 'patient_id'];

        $appointmentsTomorrow = $this->getAppointments24Hours($now->copy());
        $this->info(' * en las ultimas 24h');
        $this->table($headers, $appointmentsTomorrow->toArray());
        foreach ($appointmentsTomorrow as $appointment) {
            $appointment->patient->sendFCM('Su cita esta reservada para manana a las ' . $appointment->scheduled_time_12);
            $this->info('Mensaje FCM enviado 24h antes al paciente (ID): ' . $appointment->patient_id);
        }
        $this->info('');
        $appointmentsNextHour = $this->getAppointmentsNextHour($now->copy());
        $this->info(' * en la proxima hora');
        $this->table($headers, $appointmentsNextHour->toArray());
        foreach ($appointmentsNextHour as $appointment) {
            $appointment->patient->sendFCM('Tienes una cita en 1 hora. Te esperamos.');
            $this->info('Mensaje FCM enviado faltando 1h al paciente (ID): ' . $appointment->patient_id);
        }
    }

    private function getAppointments24Hours($now)
    {
        return Appointment::where('status', 'Confirmada')
            ->where('schedule_date', $now->addDay()->toDateString())
            ->where('scheduled_time', ">=", $now->copy()->subMinutes(3)->toTimeString())
            ->where('scheduled_time', "<", $now->copy()->subMinutes(3)->toTimeString())
            ->get(['id', 'schedule_date', 'scheduled_time', 'patient_id']);
    }

    private function getAppointmentsNextHour($now)
    {
        return Appointment::where('status', 'Confirmada')
            ->where('schedule_date', $now->addHour()->toDateString())
            ->where('scheduled_time', ">=", $now->copy()->subMinutes(3)->toTimeString())
            ->where('scheduled_time', "<", $now->copy()->subMinutes(3)->toTimeString())
            ->get(['id', 'schedule_date', 'scheduled_time', 'patient_id']);
    }
}
