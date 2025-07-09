<?php
echo '
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #8B0000;">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="#">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administración
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Usuarios</a></li>
                        <li><a class="dropdown-item" href="#">Categorías</a></li>
                        <li><a class="dropdown-item" href="#">Repuestos</a></li>
                        <li><a class="dropdown-item" href="#">Citas</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Citas</a>
                </li>
            </ul>

            <form class="d-flex me-3" role="search">
                <input class="form-control rounded-pill me-2" type="search" placeholder="Buscar..." aria-label="Buscar" style="width: 250px;">
                <button class="btn btn-outline-light rounded-pill" type="submit">Buscar</button>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
';
?>