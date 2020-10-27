<!-- Navigation -->
@if(Auth::user()->role == 'admin')
    <h6 class="navbar-heading text-muted">Gestionar datos</h6>
@else
    Menu
@endif
<ul class="navbar-nav">
    @include(
    'includes.panel.menu.' . Auth::user()->role
    )
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
