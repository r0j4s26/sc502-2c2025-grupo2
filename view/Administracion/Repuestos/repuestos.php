<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
if (!isset($_SESSION["nombreUsuario"])) {
    echo '<script> 
        alert("Debe iniciar sesión para acceder a esta página."); 
        window.location.href = "login.php"; 
    </script>';
    exit; 
}
 
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
     C.nombre as categoria,
     PR.nombre as proveedor
     FROM PRODUCTOS P 
     LEFT JOIN CATEGORIAS C ON P.id_categoria = C.id_categoria
     JOIN PROVEEDORES PR ON P.id_proveedores = PR.id_proveedores"
);

if(!$repuestos){
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Repuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <?php include '../../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Repuestos</h2>

        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Costo Unitario</th>
                    <th>Precio Venta</th>
                    <th>Proveedor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $repuestos->fetch_assoc()):?>
                    <tr>
                        <td><?= $fila['nom']?></td>
                        <td><?= $fila['descrip']?></td>
                        <td><?= $fila['marca']?></td>
                        <td><?= $fila['categoria'] ?? 'No asignada' ?></td>
                        <td><?= $fila['costoU']?></td>
                        <td><?= $fila['precioV']?></td>
                        <td><?= $fila['proveedor']?></td>
                        <td>
                            <?php if ($fila['estado'] == 1):?>
                                Activo
                            <?php else:?> 
                                Inactivo
                            <?php endif;?>
                        </td>
                        <td class="text-center">
                            <a href="modificarRepuesto.php?id_producto=<?= $fila['id_producto'] ?>" class="btn btn-primary btn-sm">Modificar</a>
                        </td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
        <div class="text-center mb-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoRepuesto">

                <i class="fas fa-plus"></i>Agregar nuevo repuesto

            </button>
        </div>
        <?php include 'agregarRepuesto.php'; ?>

</body>
</html>