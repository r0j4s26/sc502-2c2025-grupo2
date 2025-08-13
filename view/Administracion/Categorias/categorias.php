<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';

if(!isset($_SESSION["nombreUsuario"])){
    echo '<script> 
        alert("Debe iniciar sesión para acceder a esta página.") 
        window.location.href = "login.php"
        </script>';
}

$mysqli = abrirConexion();

$categorias = $mysqli->query("SELECT * from categorias ");

if(!$categorias){
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Categorias</h2>
        <div class="d-flex justify-content-center">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID Categoría</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fila = $categorias->fetch_assoc()):?>
                        <tr>
                            <td><?= $fila['id_categoria'] ?></th>
                            <td><?= $fila['nombre'] ?></th>
                            <td style="word-wrap: break-word; word-break: break-word; max-width: 300px;"><?= $fila['descripcion'] ?></th>
                            <td>
                                <?php if ($fila['estado'] == 1):?>
                                    Activo
                                <?php else:?> 
                                    Inactivo
                                <?php endif;?>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal" data-id ="<?= $fila['id_categoria'] ?>" class="btn btn-outline-danger btn-sm">Eliminar</a>
                                <a href="modificarCategoria.php?id=<?php echo $fila['id_categoria']?>" class="btn btn-primary btn-sm">Modificar</a>
                            </td>
                        </tr>
                    <?php endwhile;?>
                </tbody>
            </table>
        </div>
        <div class="text-center mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
                <i class="fas fa-plus"></i>Agregar Categoría
            </button>
        </div>

        <?php include 'agregarCategoria.php';?>
    </div>

    <div class="modal fade" id="confirmarEliminarModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="tituloModal">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta categoria? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" class="btn btn-danger" id="btnConfirmarEliminar">Sí, eliminar</a>
            </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let acciones = performance.getEntriesByType("navigation");
            if (acciones.length > 0 && acciones[0].type === "reload") {
                window.location.href = 'categorias.php';
            }
        });
    </script>

    <script>
        const modalEliminar = document.getElementById('confirmarEliminarModal');
        const btnConfirmarEliminar = document.getElementById('btnConfirmarEliminar');

        modalEliminar.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; 
            const categoriaId = button.getAttribute('data-id'); 
            btnConfirmarEliminar.href = 'eliminarCategorias.php?id=' + categoriaId;
        });
    </script>
</body>
</html>