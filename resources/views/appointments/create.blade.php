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
                    <label for="name">Especialidad:</label>
                    <select name="" id="" class="form-control">
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Medico:</label>
                    <select name="" id="" class="form-control">

                    </select>
                </div>
                <div class="form-group">
                    <label for="dni">Fecha:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input name="date" class="form-control datepicker" placeholder="Seleccionar fecha" type="text" value="06/20/2020">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Hora de atencion:</label>
                    <input type="text" name="address" class="form-control" placeholder="direccion" value="{{ old('address') }}">
                </div>
                <div class="form-group">
                    <label for="phone">Telefono / movil:</label>
                    <input type="text" name="phone" class="form-control" placeholder="telefono" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label for="phone">Contraseña:</label>
                    <input type="text" name="password" class="form-control" placeholder="contraseña" value="{{ Str::random(6) }}">
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
