<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();

$usuarios = $mysqli->query("SELECT * FROM USUARIOS");
if (!$usuarios) {
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);

require 'agregarUsuario.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

    <?php include '../../componentes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Usuarios</h2>

        <div class="text-center mb-3">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
                <i class="fas fa-plus"></i> Agregar Usuario
            </button>
        </div>

        <div class="table-responsive">
            <table id="tablaUsuarios" class="table table-bordered table-hover align-middle text-center">
                <thead class="table-danger text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fila = $usuarios->fetch_assoc()): ?>
                        <tr>
                            <td><?= $fila['id_cliente'] ?></td>
                            <td><?= $fila['nombre'] ?></td>
                            <td><?= $fila['apellidos'] ?></td>
                            <td><?= $fila['telefono'] ?></td>
                            <td><?= $fila['email'] ?></td>
                            <td>
                                <span class="badge <?= ($fila['estado'] == 1) ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ($fila['estado'] == 1) ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger me-1 btn-eliminar" data-id="<?= $fila['id_cliente'] ?>">Eliminar</button>
                                <a href="modificarUsuario.php?id_cliente=<?= $fila['id_cliente']?>" class="btn btn-sm btn-primary">Modificar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tablaUsuarios').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(location.search);

            // Usuario agregado
            if (params.get('agregado') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Usuario agregado exitosamente!',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('agregado');
            }

            // Error si se desea eliminar el usuario que está siendo usado
            if (params.get('error') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'No se puede eliminar el usuario en uso.',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('error');
            }

            // Usuario eliminado
            if (params.get('eliminado') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Usuario eliminado',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('eliminado');
            }

            // Usuario modificado
            if (params.get('modificado') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Usuario modificado',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('modificado');
            }

            // Actualizar la URL una sola vez al final, sin recargar
            const url = window.location.origin + window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            history.replaceState({}, '', url);
        });


        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-eliminar');
            if (!btn) return;
            e.preventDefault();
            const idCliente = btn.getAttribute('data-id');
            Swal.fire({
                title: '¿Esta seguro que desea eliminar este usuario?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'eliminarUsuario.php?id_cliente=' + encodeURIComponent(idCliente);
                }
            });
        });
    </script>

</body>
</html>