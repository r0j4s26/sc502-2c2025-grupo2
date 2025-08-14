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
                echo '<script>
                    window.location.href = "repuestos.php?agregado=1";
                </script>';
                exit();
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

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control <?= !empty($mensajeErrorNombre) ? 'is-invalid' : '' ?>" name="nombre" value="<?= $nombre ?>">
                <div class="invalid-feedback"><?= $mensajeErrorNombre ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control <?= !empty($mensajeErrorDescripcion) ? 'is-invalid' : '' ?>" name="descripcion" rows="3"><?= $descripcion ?></textarea>
                <div class="invalid-feedback"><?= $mensajeErrorDescripcion ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Marca</label>
                <select class="form-select <?= !empty($mensajeErrorMarca) ? 'is-invalid' : '' ?>" name="marca">
                    <option value="">Seleccione marca...</option>
                    <?php foreach ($marcas as $m): ?>
                        <option value="<?= $m ?>" <?= ($marca === $m) ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $mensajeErrorMarca ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select <?= !empty($mensajeErrorCategoria) ? 'is-invalid' : '' ?>" name="categoria">
                    <option value="">Seleccione categoría...</option>
                    <?php foreach ($categorias as $id => $nombreCat): ?>
                        <option value="<?= $id ?>" <?= ($categoria == $id) ? 'selected' : '' ?>><?= $nombreCat ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $mensajeErrorCategoria ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Proveedor</label>
                <select class="form-select <?= !empty($mensajeErrorProveedor) ? 'is-invalid' : '' ?>" name="proveedor">
                    <option value="">Seleccione proveedor...</option>
                    <?php foreach ($proveedores as $id => $nombreProv): ?>
                        <option value="<?= $id ?>" <?= ($proveedor == $id) ? 'selected' : '' ?>><?= $nombreProv ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $mensajeErrorProveedor ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Costo Unitario</label>
                <input type="number" step="0.01" class="form-control <?= !empty($mensajeErrorCosto) ? 'is-invalid' : '' ?>" name="costoUnitario" value="<?= $costoUnitario ?>">
                <div class="invalid-feedback"><?= $mensajeErrorCosto ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Precio Venta</label>
                <input type="number" step="0.01" class="form-control <?= !empty($mensajeErrorPrecio) ? 'is-invalid' : '' ?>" name="precioVenta" value="<?= $precioVenta ?>">
                <div class="invalid-feedback"><?= $mensajeErrorPrecio ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control <?= !empty($mensajeErrorStock) ? 'is-invalid' : '' ?>" name="stock" value="<?= $stock ?>">
                <div class="invalid-feedback"><?= $mensajeErrorStock ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select <?= !empty($mensajeErrorEstado) ? 'is-invalid' : '' ?>" name="estado">
                    <option value="">Seleccione estado...</option>
                    <option value="Nuevo" <?= ($estado === 'Nuevo') ? 'selected' : '' ?>>Activo</option>
                    <option value="Usado" <?= ($estado === 'Usado') ? 'selected' : '' ?>>Inactivo</option>
                </select>
                <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
            </div>

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
$hayErrores = $enviado && (
    !empty($mensajeErrorNombre) || 
    !empty($mensajeErrorDescripcion) ||
    !empty($mensajeErrorMarca) ||
    !empty($mensajeErrorCategoria) ||
    !empty($mensajeErrorProveedor) ||
    !empty($mensajeErrorCosto) ||
    !empty($mensajeErrorPrecio) ||
    !empty($mensajeErrorStock) ||
    !empty($mensajeErrorEstado)
);
?>
<?php if ($hayErrores): ?>
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