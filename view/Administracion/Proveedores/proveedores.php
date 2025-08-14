<?php
session_start();
ob_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT * FROM PROVEEDORES ORDER BY id_proveedor");
if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
}
cerrarConexion($mysqli);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores | MotoRepuestos Rojas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <?php include '../../componentes/navbar.php' ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Proveedores</h2>
        <div class="text-center mb-3">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAgregarProveedor">
                <i class="fas fa-plus"></i> Agregar Proveedor
            </button>
        </div>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-danger text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Metodo de Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php while ($u = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id_proveedor'] ?></td>
                        <td><?= $u['nombre'] ?></td>
                        <td><?= $u['telefono'] ?></td>
                        <td><?= $u['correo'] ?></td>
                        <td><?= $u['direccion'] ?></td>
                        <td><?= $u['metodo_pago'] ?></td>
                        <td><?= $u['estado'] ?></td>
                        <td>
                            <a href="eliminarProveedores.php?id=<?= $u['id_proveedor'] ?>" class="btn btn-sm btn-danger me-1 btn-eliminar">Eliminar</a>
                            <a href="modificarProveedores.php?id=<?= $u['id_proveedor'] ?>" class="btn btn-sm btn-primary">Modificar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>  

    <?php include 'agregarProveedores.php'; ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('table').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
            });
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-eliminar');
            if (!btn) return;
            e.preventDefault();
            const idProveedor = btn.getAttribute('href').split('=')[1];
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta seguro que desea eliminar este proveedor",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'eliminarProveedores.php?id=' + encodeURIComponent(idProveedor);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(location.search);

            if (params.get('agregado') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Proveedor agregado exitosamente',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('agregado');
            }


            if (params.get('modificado') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Proveedor modificado exitosamente',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('modificado');
            }
            if (params.get('eliminado') === '1') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Proveedor eliminado exitosamente',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true
                });
                params.delete('eliminado');
            }

            const url = window.location.origin + window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            history.replaceState({}, '', url);
        });
    </script>
</body>
</html>
<?php
ob_end_flush(); 
?>