<?php

//HAY QUE EDITAR ESTE NAVBAR Y LLAMARLO DONDE SEA NECESARIO
echo '
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Administrador de Tareas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/PROGRAMA_SEMANA_4/view/usuarios/listaUsuarios.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/PROGRAMA_SEMANA_4/view/login.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
'
?>