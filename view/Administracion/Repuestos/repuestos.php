<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();

$repuestos = $mysqli->query(
    "SELECT 
     P.id_producto as id_producto,
     P.nombre as nom,
     P.descripcion as descrip,
     P.marca as marca,
     P.costo_unitario as costoU,
     P.precio_venta as precioV,
     P.estado as estado,
     P.stock as stock,
     C.nombre as categoria,
     PR.nombre as proveedor
     FROM PRODUCTOS P 
     LEFT JOIN CATEGORIAS C ON P.id_categoria = C.id_categoria
     JOIN PROVEEDORES PR ON P.id_proveedor = PR.id_proveedor"
);

if(!$repuestos){
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Repuestos</title>
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

    <div class="container mt-5 mb-5">
        <h2 class="mb-4 text-center">Lista de Repuestos</h2>

        <div class="text-center mb-3">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNuevoRepuesto">
                <i class="fas fa-plus"></i> Agregar nuevo repuesto
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center" id="tablaRepuestos">
                <thead class="table-danger text-center">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Costo Unitario</th>
                        <th>Precio Venta</th>
                        <th>Proveedor</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fila = $repuestos->fetch_assoc()): ?>
                        <tr>
                            <td><?= $fila['nom']?></td>
                            <td><?= $fila['descrip']?></td>
                            <td><?= $fila['marca']?></td>
                            <td><?= $fila['categoria'] ?? 'No asignada' ?></td>
                            <td><?= $fila['costoU']?></td>
                            <td><?= $fila['precioV']?></td>
                            <td><?= $fila['proveedor']?></td>
                            <td><?= $fila['stock']?></td>
                            <td><?= ($fila['estado'] == 1) ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                                <a href="modificarRepuesto.php?id_producto=<?= $fila['id_producto'] ?>" class="btn btn-sm btn-primary">Modificar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php include 'agregarRepuesto.php'; ?>
    </div>

    <script>
        $(document).ready(function () {
            $('#tablaRepuestos').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
            });
            const params = new URLSearchParams(location.search);
            if(params.get('agregado')==='1'){
                Swal.fire({toast:true, position:'top-end', icon:'success', title:'Repuesto agregado exitosamente', showConfirmButton:false, timer:2200, timerProgressBar:true});
                params.delete('agregado');
                history.replaceState({}, '', location.pathname);
            }
            if(params.get('modificado')==='1'){
                Swal.fire({toast:true, position:'top-end', icon:'success', title:'Repuesto modificado exitosamente', showConfirmButton:false, timer:2200, timerProgressBar:true});
                params.delete('modificado');
                history.replaceState({}, '', location.pathname);
            }
        });
    </script>
</body>
</html>