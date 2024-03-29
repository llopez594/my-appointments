@extends('layouts.panel')

@section('content')
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Editar especialidad</h3>
                </div>
                <div class="col text-right">
                    <a href="{{ url('specialties') }}" class="btn btn-sm btn-default">
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
            <form action="{{ url('specialties/'.$specialty->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre de la especialidad:</label>
                    <input type="text" name="name" class="form-control" placeholder="Nombre" value="{{ old('name', $specialty->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="name">Descripcion:</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="descripcion" value="{{ old('descripcion', $specialty->descripcion) }}">
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection

