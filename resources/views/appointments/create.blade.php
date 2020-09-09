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
            <form action="{{ url('appointments') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="description">Descripcion</label>
                    <input name="description" id="description" value="{{ old('description') }}" type="text" class="form-control" placeholder="Describe brevemente la consulta" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="specialty">Especialidad:</label>
                        <select name="specialty_id" id="specialty" class="form-control" required>
                            <option value="">Seleccionar especialidad</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" @if(old('specialty_id') == $specialty->id) selected @endif>{{ $specialty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Medico:</label>
                        <select name="doctor_id" id="doctor" class="form-control" required>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @if(old('doctor_id') == $doctor->id) selected @endif>{{ $doctor->name }}</option>
                            @endforeach
                            {{--los doctores se cargan por AJAX--}}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="dni">Fecha:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input name="schedule_date" class="form-control datepicker" placeholder="Seleccionar fecha"
                               id="date" type="text"
                               value="{{ old('schedule_date', date('Y-m-d')) }}"
                               data-date-format="yyyy-mm-dd"
                               data-date-start-date="{{ date('Y-m-d') }}"
                               data-date-end-date="+30d">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Hora de atencion:</label>
                    <div id="hours">
                        @if($intervals)
                            @foreach($intervals['morning'] as $interval)

                            @endforeach
                        @else
                            <div class="alert alert-primary" role="alert">
                                Selecciona un Medico y una fecha, para ver sus horas disponibles.
                            </div>
                            {{--las horas de atencion se cargan por AJAX--}}
                        @endif
                    </div>
{{--                    <input type="text" name="address" class="form-control" placeholder="direccion" value="{{ old('address') }}">--}}
                </div>
                <div class="form-group">
                    <label for="phone">Tipo de consulta:</label>
                    <div class="custom-control custom-radio mb-3">
                        <input type="radio" id="type1" name="type" class="custom-control-input"
                               @if(old('type', 'Consulta') == 'Consulta') checked @endif value="Consulta">
                        <label class="custom-control-label" for="type1">Consulta</label>
                    </div>
                    <div class="custom-control custom-radio mb-3">
                        <input type="radio" id="type2" name="type" class="custom-control-input"
                               @if(old('type') == 'Examen') checked @endif value="Examen">
                        <label class="custom-control-label" for="type2">Examen</label>
                    </div>
                    <div class="custom-control custom-radio mb-3">
                        <input type="radio" id="type3" name="type" class="custom-control-input"
                               @if(old('type') == 'Operacion') checked @endif value="Operacion">
                        <label class="custom-control-label" for="type3">Operacion</label>
                    </div>
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
