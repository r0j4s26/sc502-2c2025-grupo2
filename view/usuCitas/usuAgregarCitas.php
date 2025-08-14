<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$mensajeExito = "";
$mensajeErrorFecha = "";
$mensajeErrorHora = "";
$mensajeErrorMotivo = "";

$fecha = "";
$hora = "";
$motivo = "";
$idUsuario = $_SESSION['idUsuario'];

$enviado = isset($_POST['submitAgregarCita']);

if ($enviado) {
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $motivo = trim($_POST['motivo'] ?? '');

    if (empty($fecha)) $mensajeErrorFecha = "Seleccione una fecha válida.";

    if (empty($hora)) {
        $mensajeErrorHora = "Seleccione una hora válida.";
    } else {
        $horaTime = strtotime($hora);
        $horaMin = strtotime('08:00');
        $horaMax = strtotime('16:00');

        if ($horaTime < $horaMin || $horaTime > $horaMax) {
            $mensajeErrorHora = "La hora debe estar entre 08:00 y 16:00.";
        }
    }

    if (strlen($motivo) < 5) $mensajeErrorMotivo = "El motivo debe tener al menos 5 caracteres.";

    if (empty($mensajeErrorFecha) && empty($mensajeErrorHora) && empty($mensajeErrorMotivo)) {
        $stmt = $mysqli->prepare("INSERT INTO CITAS (fecha, hora, motivo, id_cliente) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("sssi", $fecha, $hora, $motivo, $idUsuario);

        if ($stmt->execute()) {
            $mensajeExito = "¡Cita agendada correctamente!";
            $fecha = $hora = $motivo = "";
        } else {
            $mensajeErrorMotivo = "Ocurrió un error al agendar la cita.";
        }
    }
}
cerrarConexion($mysqli);
?>

<div class="modal fade" id="modalAgregarCita" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" novalidate>
        <div class="modal-header text-white" style="background-color:#8B0000;">
          <h5 class="modal-title">Agregar Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">


            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control <?= !empty($mensajeErrorFecha) ? 'is-invalid' : '' ?>" name="fecha" min="<?= date('Y-m-d') ?>" value="<?= $fecha ?>">
                <div class="invalid-feedback"><?= $mensajeErrorFecha ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Hora</label>
                <input type="time" class="form-control <?= !empty($mensajeErrorHora) ? 'is-invalid' : '' ?>" 
                    name="hora" 
                    value="<?= $hora ?>" 
                    min="08:00" max="16:00">
                <div class="invalid-feedback"><?= $mensajeErrorHora ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Motivo</label>
                <textarea class="form-control <?= !empty($mensajeErrorMotivo) ? 'is-invalid' : '' ?>" name="motivo" rows="3"><?= $motivo ?></textarea>
                <div class="invalid-feedback"><?= $mensajeErrorMotivo ?></div>
            </div>

            <?php if (!empty($mensajeExito)): ?>
              <div class="alert alert-success text-center"><?= $mensajeExito ?></div>
              <script>
                document.addEventListener('DOMContentLoaded', function() {
                  setTimeout(function() {
                    window.location.href = 'verCitas.php';
                  }, 3000);
                });
              </script>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="submitAgregarCita">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$hayErroresOMensajeExito = $enviado && (
    !empty($mensajeErrorFecha) ||
    !empty($mensajeErrorHora) ||
    !empty($mensajeErrorMotivo) ||
    !empty($mensajeExito)
);
?>
<?php if ($hayErroresOMensajeExito): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalAgregarCita');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
<?php endif; ?>