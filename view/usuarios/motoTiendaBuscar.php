<?php
session_start();
require_once '../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../componentes/comprobarInicio.php';

$conn = abrirConexion();
if (!$conn) { die('Error al conectar a la base de datos.'); }

$busqueda = trim($_GET['q'] ?? '');

if ($busqueda !== '') {
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.marca, p.precio_venta, p.stock,
                   c.nombre AS categoria
            FROM PRODUCTOS p
            JOIN CATEGORIAS c ON c.id_categoria = p.id_categoria
            WHERE p.estado = 1 AND p.nombre LIKE ?
            ORDER BY p.id_producto DESC
            LIMIT 100";
    $stmt = $conn->prepare($sql);
    $param = "%$busqueda%";
    $stmt->bind_param('s', $param);
    $stmt->execute();
    $res = $stmt->get_result();
    $productos = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.marca, p.precio_venta, p.stock,
                   c.nombre AS categoria
            FROM PRODUCTOS p
            JOIN CATEGORIAS c ON c.id_categoria = p.id_categoria
            WHERE p.estado = 1
            ORDER BY p.id_producto DESC
            LIMIT 100";
    $res = $conn->query($sql);
    $productos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

cerrarConexion($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MotoRepuestos Rojas | Repuestos y Accesorios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .card-agotado {
            background-color: #6c757d;
            color: #e9ecef;
        }
    </style>
</head>
<body>
    <?php include '../componentes/navbar.php'; ?>

    <div class="p-5 text-center bg-image" style="
      background-image: url('/sc502-2c2025-grupo2/img/inicio.png');
      height: 25rem; background-size: cover; background-position: center;">
      <div class="mask" style="background-color: rgba(0, 0, 0, 0.6); height: 100%;">
        <div class="d-flex justify-content-center align-items-center h-100">
          <div class="text-white">
            <h1 class="mb-3 fw-bold display-4">Catálogo de productos</h1>
            <?php if ($busqueda !== ''): ?>
                <h5 class="mb-4">Resultados de búsqueda para: "<?= htmlspecialchars($busqueda) ?>"</h5>
            <?php else: ?>
                <h5 class="mb-4">Mejor calidad y precio en un solo lugar.</h5>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-4">
      <div class="row" id="catalogo-list">
        <?php if (empty($productos)): ?>
          <div class="col-12">
            <div class="alert alert-info">No hay productos disponibles.</div>
          </div>
        <?php else: ?>
          <?php foreach ($productos as $p): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
              <div class="card h-100 shadow-sm <?= $p['stock'] == 0 ? 'card-agotado' : '' ?>">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title mb-1"><?= htmlspecialchars($p['nombre']) ?></h5>
                  <p class="text-muted mb-2">
                    <?= htmlspecialchars($p['marca'] ?? '') ?>
                    <?= ($p['marca'] && $p['categoria']) ? ' · ' : '' ?>
                    <?= htmlspecialchars($p['categoria'] ?? '') ?>
                  </p>
                  <p class="card-text small flex-grow-1">
                    <?= htmlspecialchars(mb_strimwidth($p['descripcion'] ?? '', 0, 140, '…', 'UTF-8')) ?>
                  </p>

                  <div class="d-flex justify-content-between align-items-center mt-2">
                    <strong>₡<?= number_format((float)$p['precio_venta'], 2, '.', ',') ?></strong>
                    <?php if ($p['stock'] > 0): ?>
                        <span class="badge bg-success"><?= $p['stock'] ?> disponibles</span>
                    <?php else: ?>
                        <span class="badge bg-danger">No disponible</span>
                    <?php endif; ?>
                  </div>

                  <?php if ($p['stock'] > 0): ?>
                  <form class="mt-3" method="post" action="/sc502-2c2025-grupo2/view/usuarios/carrito.php">
                    <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">
                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>">
                    <input type="hidden" name="precio" value="<?= (float)$p['precio_venta'] ?>">
                    <div class="input-group">
                      <input type="number" name="cantidad" class="form-control" value="1" min="1" max="<?= $p['stock'] ?>">
                      <button class="btn btn-danger" type="submit" name="accion" value="agregar">
                        <i class="fa fa-cart-plus me-1"></i> Agregar al carrito
                      </button>
                    </div>
                  </form>
                  <?php endif; ?>

                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>