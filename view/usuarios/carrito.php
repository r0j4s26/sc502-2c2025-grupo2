<?php
session_start();
require_once '../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../componentes/comprobarInicio.php';
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';

  if ($accion === 'agregar') {
    $id = (int)($_POST['id_producto'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));

    if ($id > 0 && $nombre !== '' && $precio >= 0) {
      $encontrado = false;
      foreach ($_SESSION['carrito'] as &$it) {
        if ($it['id_producto'] === $id) { 
          $it['cantidad'] += $cantidad; 
          $encontrado = true; 
          break; 
        }
      }
      unset($it); 

      if (!$encontrado) {
        $_SESSION['carrito'][] = [
          'id_producto'=>$id,
          'nombre'=>$nombre,
          'precio'=>$precio,
          'cantidad'=>$cantidad
        ];
      }
    }
    header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=agregado'); exit;
  }

  if ($accion === 'quitar') {
    $id = (int)($_POST['id_producto'] ?? 0);
    foreach ($_SESSION['carrito'] as $k => $it) {
      if ($it['id_producto'] === $id) { unset($_SESSION['carrito'][$k]); }
    }
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=quitado'); exit;
  }

  if ($accion === 'vaciar') {
    $_SESSION['carrito'] = [];
    header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=vaciar'); exit;
  }

  if ($accion === 'finalizar') {
    if (empty($_SESSION['carrito'])) {
      header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=vacio'); exit;
    }

    $items = [];
    $total = 0.0;

    foreach ($_SESSION['carrito'] as $it) {
      $idProd   = (int)$it['id_producto'];
      $nombre   = (string)$it['nombre'];
      $precio   = (float)$it['precio'];
      $cantidad = (int)$it['cantidad'];

      $sub = $precio * $cantidad;
      $total += $sub;

      $items[] = [
        'id_producto' => $idProd,
        'nombre'      => $nombre,
        'precio'      => $precio,
        'cantidad'    => $cantidad,
        'subtotal'    => $sub,
      ];
    }
 
    $_SESSION['checkout'] = [
      'items'     => $items,
      'total'     => $total,
      'moneda'    => 'CRC',
      'timestamp' => time(),
    ];

    header('Location: /sc502-2c2025-grupo2/view/usuarios/pago.php'); 
    exit;
  }
}

$total = 0.0; 
foreach ($_SESSION['carrito'] as $it) { 
  $total += $it['precio'] * $it['cantidad']; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Carrito</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <?php include '../componentes/navbar.php'; ?>

  <div class="container mt-4">
    <h2 class="text-center mb-4">Carrito de Compras</h2>

    <?php if (empty($_SESSION['carrito'])): ?>
      <div class="alert alert-danger">Tu carrito está vacío.</div>
      <div class="text-end">
        <a href="/sc502-2c2025-grupo2/view/usuarios/mototienda.php" class="btn btn-outline-secondary mt-2">← Seguir comprando</a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Producto</th>
              <th class="text-center">Cantidad</th>
              <th class="text-end">Precio</th>
              <th class="text-end">Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($_SESSION['carrito'] as $it): 
            $sub = $it['precio'] * $it['cantidad']; ?>
            <tr>
              <td><?= htmlspecialchars($it['nombre']) ?></td>
              <td class="text-center"><?= (int)$it['cantidad'] ?></td>
              <td class="text-end">₡<?= number_format($it['precio'], 2, '.', ',') ?></td>
              <td class="text-end">₡<?= number_format($sub, 2, '.', ',') ?></td>
              <td class="text-end">
                <form method="post" action="/sc502-2c2025-grupo2/view/usuarios/carrito.php" data-accion="quitar" class="d-inline needs-confirm">
                  <input type="hidden" name="accion" value="quitar">
                  <input type="hidden" name="id_producto" value="<?= (int)$it['id_producto'] ?>">
                  <button class="btn btn-sm btn-outline-danger">Quitar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Total</th>
              <th class="text-end">₡<?= number_format($total, 2, '.', ',') ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="d-flex gap-2 justify-content-end">
        <a href="/sc502-2c2025-grupo2/view/usuarios/mototienda.php" class="btn btn-outline-secondary">← Seguir comprando</a>
        <form method="post" action="/sc502-2c2025-grupo2/view/usuarios/carrito.php" data-accion="vaciar" class="needs-confirm m-0">
          <input type="hidden" name="accion" value="vaciar">
          <button class="btn btn-outline-danger">Vaciar carrito</button>
        </form>
        <form method="post"
      action="/sc502-2c2025-grupo2/view/usuarios/carrito.php"
      class="m-0 needs-confirm"
      data-accion="finalizar">
  <input type="hidden" name="accion" value="finalizar">
  <button class="btn btn-success" type="submit" id="btn-checkout">Finalizar compra</button>
</form>

      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="/sc502-2c2025-grupo2/scripts/carrito.js?v=5"></script>
</body>
</html>
