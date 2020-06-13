<!-- Navigation -->
@if(Auth::user()->role == 'admin')
    <h6 class="navbar-heading text-muted">Gestionar datos</h6>
@else
    Menu
@endif
<ul class="navbar-nav">
    @if(Auth::user()->role == 'admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/home') }}">
                <i class="ni ni-tv-2 text-red"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('specialties') }}">
                <i class="ni ni-planet text-blue"></i> Especialidades
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('doctors') }}">
                <i class="ni ni-pin-3 text-orange"></i> Medicos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('patients') }}">
                <i class="ni ni-satisfied text-info"></i> Pacientes
            </a>
        </li>
    @elseif(Auth::user()->role == 'doctor')
        {{--<li class="nav-item">
            <a class="nav-link" href="{{ url('/home') }}">
                <i class="ni ni-tv-2 text-red"></i> Dashboard
            </a>
        </li>--}}
        <li class="nav-item">
            <a class="nav-link" href="{{ url('specialties') }}">
                <i class="ni ni-calendar-grid-58 text-danger"></i> Gestionar horario
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('patients') }}">
                <i class="ni ni-time-alarm text-primary"></i> Mis Citas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('patients') }}">
                <i class="ni ni-satisfied text-info"></i> Mis Pacientes
            </a>
        </li>
    @else {{-- patients --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ url('specialties') }}">
                <i class="ni ni-send text-danger"></i> Mis Citas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('patients') }}">
                <i class="ni ni-time-alarm text-primary"></i> Reservar citas
            </a>
        </li>
    @endif

    {{--<li class="nav-item">
        <a class="nav-link" href="./examples/tables.html">
            <i class="ni ni-bullet-list-67 text-red"></i> Horarios
        </a>
    </li>--}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
           onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
            <i class="ni ni-key-25"></i> Cerrar sesion
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </li>
</ul>
@if(Auth::user()->role == 'admin')
<!-- Divider -->
<hr class="my-3">
<!-- Heading -->
<h6 class="navbar-heading text-muted">Reportes</h6>
<!-- Navigation -->
<ul class="navbar-nav mb-md-3">
    <li class="nav-item">
        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/getting-started/overview.html">
            <i class="ni ni-collection text-yellow"></i> Frecuencia de citas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/foundation/colors.html">
            <i class="ni ni-spaceship text-orange"></i> Medicos mas activos
        </a>
    </li>
</ul>
@endif
