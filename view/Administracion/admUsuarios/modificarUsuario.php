<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card-sombra {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .card-header-custom {
            background-color: #8B0000;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #8B0000;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #a52a2a;
        }
    </style>
</head>
<body class="bg-light">
    <?php include '../../componentes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="card card-sombra">
            <div class="card-header card-header-custom">
                Modificar Usuario
            </div>
            <div class="card-body">
                <form action="procesarModificarUsuario.php" method="POST">
                    <div class="mb-3">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" class="form-control" id="id" name="id" value="1" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="Laura" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="Gómez Ruiz" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="8888-5555" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="laura@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                        <a href="/sc502-2c2025-grupo2/view/Administracion/admUsuarios/usuarios.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>