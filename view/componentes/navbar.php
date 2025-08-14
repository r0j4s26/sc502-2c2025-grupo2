<?php
echo '
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #8B0000;">
    <div class="container-fluid">
        <a class="navbar-brand fs-4" href="/sc502-2c2025-grupo2/view/usuarios/mototienda.php">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">';

if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
    echo '
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administración
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/sc502-2c2025-grupo2/view/Administracion/admUsuarios/usuarios.php">Usuarios</a></li>
                        <li><a class="dropdown-item" href="/sc502-2c2025-grupo2/view/Administracion/Categorias/categorias.php">Categorías</a></li>
                        <li><a class="dropdown-item" href="/sc502-2c2025-grupo2/view/Administracion/Repuestos/repuestos.php">Repuestos</a></li>
                        <li><a class="dropdown-item" href="/sc502-2c2025-grupo2/view/Administracion/Citas/citas.php">Citas</a></li>
                        <li><a class="dropdown-item" href="/sc502-2c2025-grupo2/view/Administracion/Proveedores/proveedores.php">Proveedores</a></li>
                        <li><a class="dropdown-item" href="/sc502-2c2025-grupo2/view/Administracion/Pedidos/verPedidos.php">Pedidos</a></li>
                    </ul>
                </li>';
}

echo '
                <li class="nav-item">
                    <a class="nav-link" href="/sc502-2c2025-grupo2/view/usuCitas/verCitas.php">Citas</a>
                </li>
            </ul>
            <form class="d-flex me-3" role="search" action="/sc502-2c2025-grupo2/view/usuarios/motoTiendaBuscar.php" method="GET">
                <input 
                    class="form-control rounded-pill me-2" 
                    type="search" 
                    name="q" 
                    placeholder="Buscar por nombre..." 
                    aria-label="Buscar" 
                    style="width: 250px;"
                >
                <button class="btn btn-outline-light rounded-pill" type="submit">Buscar</button>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/sc502-2c2025-grupo2/view/componentes/cerrarSesion.php">Cerrar Sesión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sc502-2c2025-grupo2/view/usuarios/carrito.php">Carrito</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
';
?>