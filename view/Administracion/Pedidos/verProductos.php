<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();

$idPedido = $_GET['id'] ?? 0;
$idPedido = (int)$idPedido;

$sqlProductos = "SELECT  
        DP.id_producto,
        DP.precio,
        DP.sub_total,
        DP.cantidad,
        PR.nombre
        FROM DETALLE_PRODUCTOS_PEDIDOS DP
        JOIN PRODUCTOS PR ON DP.id_producto = PR.id_producto
        WHERE DP.id_pedido = $idPedido
        ORDER BY DP.id_producto";

$productos = $mysqli->query($sqlProductos);
if (!$productos) {
    die("Error en la consulta de productos: " . $mysqli->error);
}

$sqlPedido = "SELECT total, direccion, fecha_pedido, estado AS estadoPedido 
              FROM PEDIDOS 
              WHERE id_pedido = $idPedido";

$pedidoInfoRes = $mysqli->query($sqlPedido);
if (!$pedidoInfoRes || $pedidoInfoRes->num_rows == 0) {
    die("Pedido no encontrado.");
}
$pedidoInfo = $pedidoInfoRes->fetch_assoc();

cerrarConexion($mysqli);
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../../componentes/navbar.php'; ?>    

<div class="card shadow my-5">
    <div class="container my-3">
        <div class="card-header bg-danger text-white">
            <h3>Factura - Detalle del Pedido #<?= $idPedido ?></h3>
            <small>Fecha: <?= date('d-m-Y H:i', strtotime($pedidoInfo['fecha_pedido'])) ?></small>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $contador = 1; ?>
                    <?php while($fila = $productos->fetch_assoc()): ?>
                        <tr>
                            <td><?= $contador ?></td>
                            <td><?= $fila['nombre'] ?></td>
                            <td>CRC <?= number_format($fila['precio'], 2, ',', '.') ?></td>
                            <td><?= $fila['cantidad'] ?></td>
                            <td>CRC <?= number_format($fila['sub_total'], 2, ',', '.') ?></td>
                        </tr>
                        <?php $contador++; ?>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th>CRC <?= number_format($pedidoInfo['total'], 2, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                <p><strong>Dirección de envío:</strong> <?= $pedidoInfo['direccion'] ?></p>
                <p><strong>Estado del pedido:</strong> <?= $pedidoInfo['estadoPedido'] ?></p>
            </div>

            <a href="verPedidos.php" class="btn btn-secondary mt-3">&laquo; Regresar</a>
        </div>

        <div class="card-footer text-center">
            <small>Gracias por su compra.</small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>