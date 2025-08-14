<?php
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$mensajeErrorNombre = "";
$mensajeErrorDescripcion = "";
$mensajeErrorEstado = "";
$mensajeError = "";

$nombre = "";
$descripcion = "";
$idEstado = "";

$enviado = isset($_POST['submitAgregarCategoria']);

if ($enviado) {
    try {
        $nombre = trim($_POST["nombre"] ?? '');
        $descripcion = trim($_POST["descripcion"] ?? '');
        $idEstado = $_POST["idEstado"] ?? '';

        if (strlen($nombre) < 3 || strlen($nombre) > 50) {
            $mensajeErrorNombre = 'El nombre debe contener entre 3 y 50 caracteres.';
        }
        if (strlen($descripcion) < 10 || strlen($descripcion) > 500) {
            $mensajeErrorDescripcion = 'La descripción debe contener entre 10 y 500 caracteres.';
        }
        if ($idEstado !== '1' && $idEstado !== '0') {
            $mensajeErrorEstado = 'Debe seleccionar un estado válido.';
        }

        if (empty($mensajeErrorNombre) && empty($mensajeErrorDescripcion) && empty($mensajeErrorEstado)) {
            $stmt = $mysqli->prepare("INSERT INTO categorias (nombre, descripcion, estado) VALUES (?, ?, ?)");
            $estadoInt = (int)$idEstado;
            $stmt->bind_param("ssi", $nombre, $descripcion, $estadoInt);

            if ($stmt->execute()) {
                header("Location: categorias.php?agregado=1");
                exit();
            } else {
                $mensajeError = "Error al insertar la categoría: " . $stmt->error;
            }
        }
    } catch (Exception $e) {
        $mensajeError = "Ocurrió un error: " . $e->getMessage();
    }
}

cerrarConexion($mysqli);
?>

<div class="modal fade" id="modalAgregarCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" novalidate>
                <div class="modal-header" style="background-color:#8B0000;">
                    <h5 class="modal-title text-white">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre</label>
                        <input type="text" class="form-control <?= !empty($mensajeErrorNombre) ? 'is-invalid' : '' ?>" 
                               id="nombreCategoria" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                        <div class="invalid-feedback"><?= $mensajeErrorNombre ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcionCategoria" class="form-label">Descripción</label>
                        <textarea class="form-control <?= !empty($mensajeErrorDescripcion) ? 'is-invalid' : '' ?>" 
                                  id="descripcionCategoria" rows="3" name="descripcion"><?= htmlspecialchars($descripcion) ?></textarea>
                        <div class="invalid-feedback"><?= $mensajeErrorDescripcion ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="estadoCategoria" class="form-label">Estado</label>
                        <select class="form-select <?= !empty($mensajeErrorEstado) ? 'is-invalid' : '' ?>" 
                                id="estadoCategoria" name="idEstado">
                            <option value="">Seleccione...</option>
                            <option value="1" <?= ($idEstado === '1') ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= ($idEstado === '0') ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                        <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="submitAgregarCategoria">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

$hayErrores = $enviado && (!empty($mensajeErrorNombre) || !empty($mensajeErrorDescripcion) || !empty($mensajeErrorEstado) || !empty($mensajeError));
?>
<?php if ($hayErrores): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalAgregarCategoria');
    if (modalEl) {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
<?php endif; ?>