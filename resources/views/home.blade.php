@extends('layouts.panel')

@section('content')

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                Bienvenido! Por favor selecciona una opcion del menu lateral izquierdo.
            </div>
        </div>
    </div>

    @if (auth()->user()->role == 'admin')
        <div class="col-xl-6 mb-5 mb-xl-0">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase ls-1 mb-1">Notificacion General</h6>
                            <h2 class="mb-0">Enviar a todos los usuarios</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        @if(session('notification'))
                            <div class="alert alert-success" role="alert">
                                {{ session('notification') }}
                            </div>
                        @endif
                    </div>
                    <form action="{{ url('/fcm/send') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="title">Titulo</label>
                            <input name="title" id="title" value="{{ config('app.name') }}" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Mensaje</label>
                            <textarea name="body" id="body" cols="30" rows="2" class="form-control" required></textarea>
                        </div>
                        <button class="btn btn-primary">Enviar Notificacion</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Total de citas</h6>
                        <h2 class="mb-0">Segun dia de la semana</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Chart -->
                <div class="chart">
                    <canvas id="chart-orders" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
