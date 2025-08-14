<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();

$pedidos = $mysqli->query("SELECT  
                            P.id_pedido as id,
                            P.fecha_pedido as fecha_pedido,
                            P.fecha_entrega as fecha_entrega,
                            P.direccion as direccion,
                            P.total as total,
                            P.estado as estado,
                            U.email as email
                            FROM PEDIDOS P 
                            JOIN USUARIOS U 
                            ON P.id_cliente = U.id_cliente");

if(!$pedidos){
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
</head>
<body class="bg-light">
    <?php include '../../componentes/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="mb-4 text-center">Lista de Pedidos</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-danger text-center">
                    <tr>
                        <th>Id de pedido</th>
                        <th>Fecha Pedido</th>
                        <th>Fecha Entregado</th>
                        <th>Dirección</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Email de cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($fila = $pedidos->fetch_assoc()): ?>
                        <tr>
                            <td><?= $fila['id']?></td>
                            <td><?= $fila['fecha_pedido']?></td>
                            <td><?= $fila['fecha_entrega']?></td>
                            <td><?= $fila['direccion'] ?? 'No asignada' ?></td>
                            <td><?= $fila['total']?></td>
                            <td><?= $fila['estado']?></td>
                            <td><?= $fila['email']?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
</body>
</html>