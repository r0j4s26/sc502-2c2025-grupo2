<?php
$mysqli = abrirConexion();

$mensajeErrorNombre = $mensajeErrorApellidos = $mensajeErrorTelefono = $mensajeErrorEmail = $mensajeErrorEstado = $mensajeErrorContrasenna = $mensajeErrorConfirmContrasenna = $mensajeError = "";
$nombre = $apellidos = $telefono = $email = $estado = $contrasenna = $confirmarContrasenna = "";
$enviado = isset($_POST['submitAgregarUsuario']);

if ($enviado) {
    $nombre = trim($_POST["nombre"] ?? '');
    $apellidos = trim($_POST["apellidos"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $estado = $_POST["estado"] ?? '';
    $contrasenna = $_POST["contrasenna"] ?? '';
    $confirmarContrasenna = $_POST["confirmarContrasenna"] ?? '';

    if (strlen($nombre) < 3 || strlen($nombre) > 50) $mensajeErrorNombre = 'El nombre debe contener entre 3 y 50 caracteres.';
    if (strlen($apellidos) < 3 || strlen($apellidos) > 50) $mensajeErrorApellidos = 'Los apellidos deben contener entre 3 y 50 caracteres.';
    if (!preg_match('/^[0-9]{8}$/', $telefono)) $mensajeErrorTelefono = 'El teléfono debe tener 8 dígitos.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $mensajeErrorEmail = 'Debe ingresar un correo válido.';
    if ($estado !== '1' && $estado !== '0') $mensajeErrorEstado = 'Debe seleccionar un estado válido.';
    if (strlen($contrasenna) < 6) $mensajeErrorContrasenna = 'La contraseña debe tener al menos 6 caracteres.';
    if ($contrasenna !== $confirmarContrasenna) $mensajeErrorConfirmContrasenna = 'Las contraseñas no coinciden.';

    if (empty($mensajeErrorNombre) && empty($mensajeErrorApellidos) && empty($mensajeErrorTelefono) && empty($mensajeErrorEmail) && empty($mensajeErrorEstado) && empty($mensajeErrorContrasenna) && empty($mensajeErrorConfirmContrasenna)) {
        $contrasennaHash = password_hash($contrasenna, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, apellidos, telefono, email, estado, contrasena) VALUES (?, ?, ?, ?, ?, ?)");
        $estadoInt = (int)$estado;
        $stmt->bind_param("ssssis", $nombre, $apellidos, $telefono, $email, $estadoInt, $contrasennaHash);

        if ($stmt->execute()) {
            header("Location: usuarios.php?agregado=1");
            exit();
        } else {
            $mensajeError = "Error al insertar el usuario: " . $stmt->error;
        }
    }
}

cerrarConexion($mysqli);
?>

<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" novalidate>
        <div class="modal-header text-white" style="background-color:#8B0000;">
          <h5 class="modal-title">Agregar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <?php if (!empty($mensajeError)): ?>
              <div class="alert alert-danger"><?= $mensajeError ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control <?= !empty($mensajeErrorNombre)?'is-invalid':'' ?>" name="nombre" value="<?= $nombre ?>">
                <?php if($enviado && $mensajeErrorNombre): ?><div class="invalid-feedback"><?= $mensajeErrorNombre ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellidos</label>
                <input type="text" class="form-control <?= !empty($mensajeErrorApellidos)?'is-invalid':'' ?>" name="apellidos" value="<?= $apellidos ?>">
                <?php if($enviado && $mensajeErrorApellidos): ?><div class="invalid-feedback"><?= $mensajeErrorApellidos ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control <?= !empty($mensajeErrorTelefono)?'is-invalid':'' ?>" name="telefono" value="<?= $telefono ?>">
                <?php if($enviado && $mensajeErrorTelefono): ?><div class="invalid-feedback"><?= $mensajeErrorTelefono ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control <?= !empty($mensajeErrorEmail)?'is-invalid':'' ?>" name="email" value="<?= $email ?>">
                <?php if($enviado && $mensajeErrorEmail): ?><div class="invalid-feedback"><?= $mensajeErrorEmail ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select <?= !empty($mensajeErrorEstado)?'is-invalid':'' ?>" name="estado">
                    <option value="">Seleccione...</option>
                    <option value="1" <?= $estado==='1'?'selected':'' ?>>Activo</option>
                    <option value="0" <?= $estado==='0'?'selected':'' ?>>Inactivo</option>
                </select>
                <?php if($enviado && $mensajeErrorEstado): ?><div class="invalid-feedback"><?= $mensajeErrorEstado ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control <?= !empty($mensajeErrorContrasenna)?'is-invalid':'' ?>" name="contrasenna">
                <?php if($enviado && $mensajeErrorContrasenna): ?><div class="invalid-feedback"><?= $mensajeErrorContrasenna ?></div><?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control <?= !empty($mensajeErrorConfirmContrasenna)?'is-invalid':'' ?>" name="confirmarContrasenna">
                <?php if($enviado && $mensajeErrorConfirmContrasenna): ?><div class="invalid-feedback"><?= $mensajeErrorConfirmContrasenna ?></div><?php endif; ?>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="submitAgregarUsuario">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if($enviado && ($mensajeErrorNombre || $mensajeErrorApellidos || $mensajeErrorTelefono || $mensajeErrorEmail || $mensajeErrorEstado || $mensajeErrorContrasenna || $mensajeErrorConfirmContrasenna || $mensajeError)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalAgregarUsuario');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
});
</script>
<?php endif; ?>