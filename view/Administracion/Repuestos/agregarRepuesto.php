<?php 
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();

$categorias = [];
$result = $mysqli->query("SELECT id_categoria, nombre FROM CATEGORIAS WHERE estado = 1");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categorias[$row['id_categoria']] = $row['nombre'];
    }
}

$proveedores = [];
$result = $mysqli->query("SELECT id_proveedor, nombre FROM PROVEEDORES WHERE estado = 1");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $proveedores[$row['id_proveedor']] = $row['nombre'];
    }
}

$marcas = [
    "Honda", "Nissan", "Toyota", "Mazda", "Ford", "Chevrolet", "Kia", "Hyundai",
    "Volkswagen", "Mitsubishi", "Suzuki", "Renault", "Fiat", "Peugeot", "Citroën", "Chery", "Geely"
];

$mensajeExito = "";
$mensajeErrorNombre = "";
$mensajeErrorDescripcion = "";
$mensajeErrorMarca = "";
$mensajeErrorCategoria = "";
$mensajeErrorCosto = "";
$mensajeErrorPrecio = "";
$mensajeErrorEstado = "";
$mensajeErrorProveedor = "";
$mensajeErrorStock = "";
$mensajeError = "";

$nombre = "";
$descripcion = "";
$marca = "";
$categoria = "";
$costoUnitario = "";
$precioVenta = "";
$estado = "";
$proveedor = "";
$stock = "";

$enviado = isset($_POST['submitNuevoRepuesto']);

if ($enviado) {
    try {
        $nombre = trim($_POST["nombre"] ?? '');
        $descripcion = trim($_POST["descripcion"] ?? '');
        $marca = $_POST["marca"] ?? '';
        $categoria = $_POST["categoria"] ?? '';
        $costoUnitario = trim($_POST["costoUnitario"] ?? '');
        $precioVenta = trim($_POST["precioVenta"] ?? '');
        $estado = $_POST["estado"] ?? '';
        $proveedor = $_POST["proveedor"] ?? '';
        $stock = trim($_POST["stock"] ?? '');

        if (strlen($nombre) < 3 || strlen($nombre) > 100) $mensajeErrorNombre = 'El nombre debe contener entre 3 y 100 caracteres.';
        if (strlen($descripcion) < 5) $mensajeErrorDescripcion = 'La descripción es demasiado corta.';
        if (!in_array($marca, $marcas)) $mensajeErrorMarca = 'Debe seleccionar una marca válida.';
        if (!array_key_exists($categoria, $categorias)) $mensajeErrorCategoria = 'Debe seleccionar una categoría válida.';
        if (!is_numeric($costoUnitario) || floatval($costoUnitario) <= 0) $mensajeErrorCosto = 'El costo unitario debe ser un número mayor a 0.';
        if (!is_numeric($precioVenta) || floatval($precioVenta) <= 0) $mensajeErrorPrecio = 'El precio de venta debe ser un número mayor a 0.';
        if ($estado !== 'Nuevo' && $estado !== 'Usado') $mensajeErrorEstado = 'Debe seleccionar un estado válido.';
        if (!array_key_exists($proveedor, $proveedores)) $mensajeErrorProveedor = 'Debe seleccionar un proveedor válido.';
        if (!is_numeric($stock) || intval($stock) < 0) $mensajeErrorStock = 'El stock debe ser un número entero mayor o igual a 0.';

        if (empty($mensajeErrorNombre) && empty($mensajeErrorDescripcion) && empty($mensajeErrorMarca) &&
            empty($mensajeErrorCategoria) && empty($mensajeErrorCosto) && empty($mensajeErrorPrecio) &&
            empty($mensajeErrorEstado) && empty($mensajeErrorProveedor) && empty($mensajeErrorStock)) {

            $estadoInt = ($estado === 'Nuevo') ? 1 : 0;
            $stmt = $mysqli->prepare("
                INSERT INTO PRODUCTOS (nombre, descripcion, marca, costo_unitario, precio_venta, estado, stock, id_categoria, id_proveedor) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("sssddiiii", $nombre, $descripcion, $marca, $costoUnitario, $precioVenta, $estadoInt, $stock, $categoria, $proveedor);

            if ($stmt->execute()) {
                $mensajeExito = "¡Repuesto agregado exitosamente! Redirigiendo...";
            } else {
                $mensajeError = "Error al insertar el repuesto: " . $stmt->error;
            }
        }
    } catch (Exception $e) {
        $mensajeError = "Ocurrió un error: " . $e->getMessage();
    }
}

cerrarConexion($mysqli);
?>
<div class="modal fade" id="modalNuevoRepuesto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" novalidate>
        <div class="modal-header text-white" style="background-color:#8B0000;">
          <h5 class="modal-title">Registrar Nuevo Repuesto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">

            <?php if (!empty($mensajeError)): ?>
              <div class="alert alert-danger"><?= $mensajeError ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?= $nombre ?>">
                <?php if ($enviado && !empty($mensajeErrorNombre)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorNombre ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3"><?= $descripcion ?></textarea>
                <?php if ($enviado && !empty($mensajeErrorDescripcion)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorDescripcion ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Marca</label>
                <select class="form-select" name="marca">
                    <option value="">Seleccione marca...</option>
                    <?php foreach ($marcas as $m): ?>
                        <option value="<?= $m ?>" <?= ($marca === $m) ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($enviado && !empty($mensajeErrorMarca)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorMarca ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="categoria">
                    <option value="">Seleccione categoría...</option>
                    <?php foreach ($categorias as $id => $nombreCat): ?>
                        <option value="<?= $id ?>" <?= ($categoria == $id) ? 'selected' : '' ?>><?= $nombreCat ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($enviado && !empty($mensajeErrorCategoria)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorCategoria ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Proveedor</label>
                <select class="form-select" name="proveedor">
                    <option value="">Seleccione proveedor...</option>
                    <?php foreach ($proveedores as $id => $nombreProv): ?>
                        <option value="<?= $id ?>" <?= ($proveedor == $id) ? 'selected' : '' ?>><?= $nombreProv ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($enviado && !empty($mensajeErrorProveedor)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorProveedor ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Costo Unitario</label>
                <input type="number" step="0.01" class="form-control" name="costoUnitario" value="<?= $costoUnitario ?>">
                <?php if ($enviado && !empty($mensajeErrorCosto)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorCosto ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio Venta</label>
                <input type="number" step="0.01" class="form-control" name="precioVenta" value="<?= $precioVenta ?>">
                <?php if ($enviado && !empty($mensajeErrorPrecio)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorPrecio ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" value="<?= $stock ?>">
                <?php if ($enviado && !empty($mensajeErrorStock)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorStock ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="">Seleccione estado...</option>
                    <option value="Nuevo" <?= ($estado === 'Nuevo') ? 'selected' : '' ?>>Activo</option>
                    <option value="Usado" <?= ($estado === 'Usado') ? 'selected' : '' ?>>Inactivo</option>
                </select>
                <?php if ($enviado && !empty($mensajeErrorEstado)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorEstado ?></div>
                <?php endif; ?>
            </div>

            <?php if (!empty($mensajeExito)): ?>
              <div class="alert alert-success text-center"><?= $mensajeExito ?></div>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                  setTimeout(function() {
                    window.location.href = 'repuestos.php';
                  }, 3000);
                });
              </script>
            <?php endif; ?>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="submitNuevoRepuesto">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$hayErroresOMensajeExito = $enviado && (
    !empty($mensajeErrorNombre) || 
    !empty($mensajeErrorDescripcion) ||
    !empty($mensajeErrorMarca) ||
    !empty($mensajeErrorCategoria) ||
    !empty($mensajeErrorProveedor) ||
    !empty($mensajeErrorCosto) ||
    !empty($mensajeErrorPrecio) ||
    !empty($mensajeErrorStock) ||
    !empty($mensajeErrorEstado) ||
    !empty($mensajeError) ||
    !empty($mensajeExito)
);
?>
<?php if ($hayErroresOMensajeExito): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalNuevoRepuesto');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
<?php endif; ?>