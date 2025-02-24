<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('cliente.index') }}">
                <i class="bi bi-people-fill"></i>
                <span>Ordenes diarias</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#module-empresarial" data-bs-toggle="collapse" href="#">
            <i class="bi bi-building-check"></i><span>Empresarial</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="module-empresarial" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('app.empresa') }}">
                        <i class="bi bi-circle"></i><span>Empresas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.empleados.index') }}">
                        <i class="bi bi-circle"></i><span>Colaboradores</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('lab.resultado.index') }}">
                        <i class="bi bi-circle"></i><span>Ordenes</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#examenes-items-admins" data-bs-toggle="collapse" href="#">

                <i class="bi bi-person-arms-up"></i><span>Examenes</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="examenes-items-admins" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('app.perfEx') }}">
                        <i class="bi bi-circle"></i><span>examenes</span>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('app.user') }}">
                <i class="bi bi-person-circle"></i>
                <span>Usuario</span>
            </a>
        </li><!-- End Blank Page Nav -->
    </ul>
</aside><!-- End Sidebar-->