<?php
if (!isset($_SESSION["nombreUsuario"])) {
    echo '<script>
        alert("Debe iniciar sesión para acceder a esta página.");
        window.location.href = "../login.php";
    </script>';
    exit();
}

$mysqli = abrirConexion();

$mensajeExito = "";
$mensajeErrorNombre = "";
$mensajeErrorApellidos = "";
$mensajeErrorTelefono = "";
$mensajeErrorEmail = "";
$mensajeErrorEstado = "";
$mensajeError = "";

$nombre = "";
$apellidos = "";
$telefono = "";
$email = "";
$estado = "";

$enviado = isset($_POST['submitAgregarUsuario']);

if ($enviado) {
    try {
        $nombre = trim($_POST["nombre"] ?? '');
        $apellidos = trim($_POST["apellidos"] ?? '');
        $telefono = trim($_POST["telefono"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $estado = $_POST["estado"] ?? '';

        if (strlen($nombre) < 3 || strlen($nombre) > 50) {
            $mensajeErrorNombre = 'El nombre debe contener entre 3 y 50 caracteres.';
        }
        if (strlen($apellidos) < 3 || strlen($apellidos) > 50) {
            $mensajeErrorApellidos = 'Los apellidos deben contener entre 3 y 50 caracteres.';
        }
        if (!preg_match('/^[0-9]{8}$/', $telefono)) {
            $mensajeErrorTelefono = 'El teléfono debe tener 8 dígitos numéricos.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensajeErrorEmail = 'Debe ingresar un correo válido.';
        }
        if ($estado !== '1' && $estado !== '0') {
            $mensajeErrorEstado = 'Debe seleccionar un estado válido.';
        }

        if (empty($mensajeErrorNombre) && empty($mensajeErrorApellidos) && empty($mensajeErrorTelefono) && empty($mensajeErrorEmail) && empty($mensajeErrorEstado)) {
            $estadoInt = (int)$estado; 
            $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, apellidos, telefono, email, estado) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $nombre, $apellidos, $telefono, $email, $estadoInt);

            if ($stmt->execute()) {
                $mensajeExito = "¡Usuario agregado exitosamente! Redirigiendo...";
            } else {
                $mensajeError = "Error al insertar el usuario: " . $stmt->error;
            }
        }
    } catch (Exception $e) {
        $mensajeError = "Ocurrió un error: " . $e->getMessage();
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
                <label for="nombreUsuario" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombreUsuario" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                <?php if ($enviado && !empty($mensajeErrorNombre)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorNombre ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="apellidosUsuario" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidosUsuario" name="apellidos" value="<?= htmlspecialchars($apellidos) ?>">
                <?php if ($enviado && !empty($mensajeErrorApellidos)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorApellidos ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="telefonoUsuario" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefonoUsuario" name="telefono" value="<?= htmlspecialchars($telefono) ?>">
                <?php if ($enviado && !empty($mensajeErrorTelefono)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorTelefono ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="emailUsuario" class="form-label">Email</label>
                <input type="email" class="form-control" id="emailUsuario" name="email" value="<?= htmlspecialchars($email) ?>">
                <?php if ($enviado && !empty($mensajeErrorEmail)): ?>
                    <div class="alert alert-danger mt-2"><?= $mensajeErrorEmail ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="estadoUsuario" class="form-label">Estado</label>
                <select class="form-select" id="estadoUsuario" name="estado">
                    <option value="">Seleccione...</option>
                    <option value="1" <?= ($estado === '1') ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= ($estado === '0') ? 'selected' : '' ?>>Inactivo</option>
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
                    window.location.href = 'usuarios.php';
                  }, 3000);
                });
              </script>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="submitAgregarUsuario">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$hayErroresOMensajeExito = $enviado && (
    !empty($mensajeErrorNombre) || 
    !empty($mensajeErrorApellidos) || 
    !empty($mensajeErrorTelefono) || 
    !empty($mensajeErrorEmail) || 
    !empty($mensajeErrorEstado) || 
    !empty($mensajeError) || 
    !empty($mensajeExito)
);
?>
<?php if ($hayErroresOMensajeExito): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalAgregarUsuario');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
<?php endif; ?>