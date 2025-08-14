<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$mensajeExito = "";
$mensajeErrorNombre = "";
$mensajeErrorTelefono = "";
$mensajeErrorCorreo = "";
$mensajeErrorDireccion = "";
$mensajeErrorMetodoPago = "";
$mensajeErrorEstado = "";
$mensajeError = "";

$enviado = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($enviado) {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if (strlen($nombre) < 3 || strlen($nombre) > 50) $mensajeErrorNombre = 'El nombre debe contener entre 3 y 50 caracteres.';
    if (!preg_match('/^[0-9]{8}$/', $telefono)) $mensajeErrorTelefono = 'El teléfono debe contener 8 dígitos numéricos.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $mensajeErrorCorreo = 'Ingrese un correo válido.';
    if (strlen($direccion) < 3 || strlen($direccion) > 100) $mensajeErrorDireccion = 'La dirección debe contener entre 3 y 100 caracteres.';
    if ($metodo_pago !== 'Contado' && $metodo_pago !== 'Credito') $mensajeErrorMetodoPago = 'Seleccione un método de pago válido.';
    if ($estado !== 'Activo' && $estado !== 'Inactivo') $mensajeErrorEstado = 'Seleccione un estado válido.';

    if (empty($mensajeErrorNombre) && empty($mensajeErrorTelefono) && empty($mensajeErrorCorreo) && empty($mensajeErrorDireccion) && empty($mensajeErrorMetodoPago) && empty($mensajeErrorEstado)) {
        try {
            $telefonoInt = intval($telefono);
            $estadoInt = ($estado === 'Activo') ? 1 : 0;
            $stmt = $mysqli->prepare("INSERT INTO PROVEEDORES (nombre, telefono, correo, direccion, metodo_pago, estado) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("sisssi", $nombre, $telefonoInt, $correo, $direccion, $metodo_pago, $estadoInt);

            if ($stmt->execute()) {
                cerrarConexion($mysqli);
                $mensajeExito = "¡Proveedor agregado correctamente! Redirigiendo...";
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(function() {
                                window.location.href = 'proveedores.php';
                            }, 2000);
                        });
                      </script>";
            } else {
                $mensajeError = "Error al insertar el proveedor: " . $stmt->error;
            }
        } catch (Exception $e) {
            $mensajeError = "Ocurrió un error: " . $e->getMessage();
        }
    }
}

cerrarConexion($mysqli);
?>

<div class="modal fade" id="modalAgregarProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" novalidate>
                <div class="modal-header" style="background-color:#8B0000;">
                    <h5 class="modal-title text-white">Agregar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($mensajeError)): ?>
                        <div class="alert alert-danger" role="alert"><?= $mensajeError ?></div>
                    <?php endif; ?>

                    <?php if (!empty($mensajeExito)): ?>
                        <div class="alert alert-success text-center"><?= $mensajeExito ?></div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control <?= !empty($mensajeErrorNombre) ? 'is-invalid' : '' ?>" maxlength="50" value="<?= $_POST['nombre'] ?? '' ?>">
                            <?php if (!empty($mensajeErrorNombre)): ?>
                                <div class="invalid-feedback"><?= $mensajeErrorNombre ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control <?= !empty($mensajeErrorTelefono) ? 'is-invalid' : '' ?>" maxlength="8" value="<?= $_POST['telefono'] ?? '' ?>">
                            <?php if (!empty($mensajeErrorTelefono)): ?>
                                <div class="invalid-feedback"><?= $mensajeErrorTelefono ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control <?= !empty($mensajeErrorCorreo) ? 'is-invalid' : '' ?>" maxlength="50" value="<?= $_POST['correo'] ?? '' ?>">
                            <?php if (!empty($mensajeErrorCorreo)): ?>
                                <div class="invalid-feedback"><?= $mensajeErrorCorreo ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control <?= !empty($mensajeErrorDireccion) ? 'is-invalid' : '' ?>" maxlength="100" value="<?= $_POST['direccion'] ?? '' ?>">
                            <?php if (!empty($mensajeErrorDireccion)): ?>
                                <div class="invalid-feedback"><?= $mensajeErrorDireccion ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Método de pago</label>
                            <select name="metodo_pago" class="form-select <?= !empty($mensajeErrorMetodoPago) ? 'is-invalid' : '' ?>">
                                <option value="">--Seleccione un método--</option>
                                <option value="Contado" <?= (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] === 'Contado') ? 'selected' : '' ?>>Contado</option>
                                <option value="Credito" <?= (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] === 'Credito') ? 'selected' : '' ?>>Credito</option>
                            </select>
                            <?php if (!empty($mensajeErrorMetodoPago)): ?>
                                <div class="invalid-feedback"><?= $mensajeErrorMetodoPago ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select <?= !empty($mensajeErrorEstado) ? 'is-invalid' : '' ?>">
                                <option value="">Seleccione un estado</option>
                                <option value="Activo" <?= (isset($_POST['estado']) && $_POST['estado'] === 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= (isset($_POST['estado']) && $_POST['estado'] === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                            <?php if (!empty($mensajeErrorEstado)): ?>
                                <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="submitAgregarProveedor" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$mostrarModal = $enviado && (!empty($mensajeErrorNombre) || !empty($mensajeErrorTelefono) || !empty($mensajeErrorCorreo) || !empty($mensajeErrorDireccion) || !empty($mensajeErrorMetodoPago) || !empty($mensajeErrorEstado) || !empty($mensajeError) || !empty($mensajeExito));
?>
<?php if ($mostrarModal): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalAgregarProveedor');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
<?php endif; ?>