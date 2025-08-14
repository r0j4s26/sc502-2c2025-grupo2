<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();
$categorias = $mysqli->query("SELECT * FROM categorias");
if (!$categorias) {
    die("Error en la consulta: " . $mysqli->error);
}
cerrarConexion($mysqli);

require 'agregarCategoria.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administración de Categorías</title>
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
    <h2 class="mb-4 text-center">Lista de Categorías</h2>

    <div class="text-center mb-3">
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
            <i class="fas fa-plus"></i> Agregar Categoría
        </button>
    </div>

    <div class="table-responsive">
        <table id="tablaCategorias" class="table table-bordered table-hover align-middle text-center">
            <thead class="table-danger text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $categorias->fetch_assoc()): ?>
                    <tr>
                        <td><?= $fila['id_categoria'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td style="word-wrap: break-word; word-break: break-word; max-width: 300px;"><?= $fila['descripcion'] ?></td>
                        <td>
                            <span class="badge <?= ($fila['estado']==1)?'bg-success':'bg-danger' ?>">
                                <?= ($fila['estado']==1)?'Activo':'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger me-1 btn-eliminar" data-id="<?= $fila['id_categoria'] ?>">Eliminar</button>
                            <a href="modificarCategoria.php?id=<?= $fila['id_categoria'] ?>" class="btn btn-sm btn-primary">Modificar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
$(document).ready(function () {
    $('#tablaCategorias').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(location.search);

    if (params.get('agregado') === '1') {
        Swal.fire({toast:true,position:'top-end',icon:'success',title:'¡Categoría agregada!',showConfirmButton:false,timer:2200,timerProgressBar:true});
        params.delete('agregado');
    }
    if (params.get('modificado') === '1') {
        Swal.fire({toast:true,position:'top-end',icon:'success',title:'¡Categoría modificada!',showConfirmButton:false,timer:2200,timerProgressBar:true});
        params.delete('modificado');
    }
    if (params.get('eliminado') === '1') {
        Swal.fire({toast:true,position:'top-end',icon:'success',title:'¡Categoría eliminada!',showConfirmButton:false,timer:2200,timerProgressBar:true});
        params.delete('eliminado');
    }

    const newQuery = params.toString();
    const newUrl = window.location.origin + window.location.pathname + (newQuery ? '?' + newQuery : '');
    history.replaceState({}, '', newUrl);
});

document.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-eliminar');
    if(!btn) return;
    e.preventDefault();
    const idCategoria = btn.getAttribute('data-id');
    Swal.fire({
        title: '¿Esta seguro que desea eliminar esta categoria?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href = 'eliminarCategorias.php?id=' + encodeURIComponent(idCategoria);
        }
    });
});
</script>

</body>
</html>