<?php
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$mensajeErrorNombre = "";
$mensajeErrorTelefono = "";
$mensajeErrorCorreo = "";
$mensajeErrorDireccion = "";
$mensajeErrorMetodoPago = "";
$mensajeErrorEstado = "";

$nombre = "";
$telefono = "";
$correo = "";
$direccion = "";
$metodo_pago = "";
$estado = "";

$enviado = isset($_POST['submitAgregarProveedor']);

if ($enviado) {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if (strlen($nombre) < 3 || strlen($nombre) > 50) $mensajeErrorNombre = "Nombre inválido.";
    if (!preg_match('/^[0-9]{8}$/', $telefono)) $mensajeErrorTelefono = "Teléfono inválido.";
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $mensajeErrorCorreo = "Correo inválido.";
    if (strlen($direccion) < 3) $mensajeErrorDireccion = "Dirección inválida.";
    if (!in_array($metodo_pago, ['Contado', 'Credito'])) $mensajeErrorMetodoPago = "Método de pago inválido.";
    if (!in_array($estado, ['Activo', 'Inactivo'])) $mensajeErrorEstado = "Estado inválido.";

    if (empty($mensajeErrorNombre) && empty($mensajeErrorTelefono) && empty($mensajeErrorCorreo) &&
        empty($mensajeErrorDireccion) && empty($mensajeErrorMetodoPago) && empty($mensajeErrorEstado)) {

        $telefonoInt = intval($telefono);
        $estadoInt = ($estado === 'Activo') ? 1 : 0;
        $stmt = $mysqli->prepare("INSERT INTO PROVEEDORES(nombre, telefono, correo, direccion, metodo_pago, estado) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("sisssi", $nombre, $telefonoInt, $correo, $direccion, $metodo_pago, $estadoInt);

        if ($stmt->execute()) {
            cerrarConexion($mysqli);
            echo '<script>
                window.location.href = "proveedores.php?agregado=1";
            </script>';
            exit();
        }
    }
}
cerrarConexion($mysqli);
?>

<div class="modal fade" id="modalAgregarProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" novalidate>
                <div class="modal-header text-white" style="background-color:#8B0000;">
                    <h5 class="modal-title">Agregar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-2">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control <?= !empty($mensajeErrorNombre) ? 'is-invalid' : '' ?>" value="<?= $nombre ?>">
                        <div class="invalid-feedback"><?= $mensajeErrorNombre ?></div>
                    </div>

                    <div class="mb-2">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control <?= !empty($mensajeErrorTelefono) ? 'is-invalid' : '' ?>" value="<?= $telefono ?>">
                        <div class="invalid-feedback"><?= $mensajeErrorTelefono ?></div>
                    </div>

                    <div class="mb-2">
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control <?= !empty($mensajeErrorCorreo) ? 'is-invalid' : '' ?>" value="<?= $correo ?>">
                        <div class="invalid-feedback"><?= $mensajeErrorCorreo ?></div>
                    </div>

                    <div class="mb-2">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control <?= !empty($mensajeErrorDireccion) ? 'is-invalid' : '' ?>" value="<?= $direccion ?>">
                        <div class="invalid-feedback"><?= $mensajeErrorDireccion ?></div>
                    </div>

                    <div class="mb-2">
                        <label>Método de Pago</label>
                        <select name="metodo_pago" class="form-select <?= !empty($mensajeErrorMetodoPago) ? 'is-invalid' : '' ?>">
                            <option value="">--Seleccione--</option>
                            <option value="Contado" <?= ($metodo_pago=='Contado')?'selected':'' ?>>Contado</option>
                            <option value="Credito" <?= ($metodo_pago=='Credito')?'selected':'' ?>>Credito</option>
                        </select>
                        <div class="invalid-feedback"><?= $mensajeErrorMetodoPago ?></div>
                    </div>

                    <div class="mb-2">
                        <label>Estado</label>
                        <select name="estado" class="form-select <?= !empty($mensajeErrorEstado) ? 'is-invalid' : '' ?>">
                            <option value="">--Seleccione--</option>
                            <option value="Activo" <?= ($estado=='Activo')?'selected':'' ?>>Activo</option>
                            <option value="Inactivo" <?= ($estado=='Inactivo')?'selected':'' ?>>Inactivo</option>
                        </select>
                        <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit" name="submitAgregarProveedor">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$hayErrores = $enviado && (
    !empty($mensajeErrorNombre) ||
    !empty($mensajeErrorTelefono) ||
    !empty($mensajeErrorCorreo) ||
    !empty($mensajeErrorDireccion) ||
    !empty($mensajeErrorMetodoPago) ||
    !empty($mensajeErrorEstado)
);
?>

<?php if ($hayErrores): ?>
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