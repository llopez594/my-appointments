<div class="table-responsive">
    <!-- Projects table -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th scope="col">Descripcion</th>
            <th scope="col">Especialidad</th>
            @if($role == 'patient')
                <th scope="col">Medico</th>
            @elseif($role == 'doctor')
                <th scope="col">Paciente</th>
            @endif
            <th scope="col">Fecha</th>
            <th scope="col">Hora</th>
            <th scope="col">Tipo</th>
            <th scope="col">Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pendingAppointments as $appointment)
            <tr>
                <th scope="row">
                    {{ $appointment->description }}
                </th>
                <td>
                    {{ $appointment->specialty->name }}
                </td>
                @if($role == 'patient')
                    <td>
                        {{ $appointment->doctor->name }}
                    </td>
                @elseif($role == 'doctor')
                    <td>
                        {{ $appointment->patient->name }}
                    </td>
                @endif
                <td>
                    {{ $appointment->schedule_date }}
                </td>
                <td>
                    {{ $appointment->scheduled_time_12 }}
                </td>
                <td>
                    {{ $appointment->type }}
                </td>
                <td>
                    @if($role == 'admin')
                        <a class="btn btn-primary btn-sm" href="{{ url('/appointments/'.$appointment->id) }}" title="Ver cita">
                            Ver
                        </a>
                    @endif
                    @if($role == 'doctor' || $role == 'admin')
                        <form action="{{ url('appointments/'.$appointment->id.'/confirm') }}" method="POST" class="d-inline-block">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Confirmar cita" data-toggle="tooltip" data-placement="top">
                                <i class="ni ni-check-bold"></i>
                            </button>
                        </form>
                    @endif
                    <form action="{{ url('appointments/'.$appointment->id.'/cancel') }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" title="Cancelar cita" data-toggle="tooltip" data-placement="top">
                            <i class="ni ni-fat-delete"></i>
                        </button>
                    </form>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="card-body">
    {{ $pendingAppointments->links() }}
</div>
