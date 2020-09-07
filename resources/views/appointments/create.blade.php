@extends('layouts.panel')

@section('content')
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Registrar nueva cita</h3>
                </div>
                <div class="col text-right">
                    <a href="{{ url('appointments') }}" class="btn btn-sm btn-default">
                        Cancelar y volver
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
                <ul>
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                </ul>
            @endif
            <form action="{{ url('patients') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="specialty">Especialidad:</label>
                    <select name="specialty_id" id="specialty" class="form-control" required>
                        <option value="">Seleccionar especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Medico:</label>
                    <select name="doctor_id" id="doctor" class="form-control">
                        {{--los doctores se cargan por AJAX--}}
                    </select>
                </div>
                <div class="form-group">
                    <label for="dni">Fecha:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input name="date" class="form-control datepicker" placeholder="Seleccionar fecha"
                               id="date" type="text" value="{{ date('Y-m-d') }}"
                               data-date-format="yyyy-mm-dd"
                               data-date-start-date="{{ date('Y-m-d') }}"
                               data-date-end-date="+30d">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Hora de atencion:</label>
                    <div id="hours">

                    </div>
{{--                    <input type="text" name="address" class="form-control" placeholder="direccion" value="{{ old('address') }}">--}}
                </div>
                <div class="form-group">
                    <label for="phone">Telefono / movil:</label>
                    <input type="text" name="phone" class="form-control" placeholder="telefono" value="{{ old('phone') }}">
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/js/appointments/create.js') }}"></script>
@endsection
