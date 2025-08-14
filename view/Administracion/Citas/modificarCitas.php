<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$id = $_GET['id'];
$cita = $mysqli->query("SELECT * FROM CITAS WHERE id_cita = $id")->fetch_assoc();

$mensajeErrorFecha = $mensajeErrorHora = $mensajeErrorEstado = $mensajeErrorMotivo = "";

$fecha = $cita['fecha'];
$hora = $cita['hora'];
$estado = $cita['estado'];
$motivo = $cita['motivo'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $fecha = $_POST['fecha'] ?? $cita['fecha'];
    $hora = $_POST['hora'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $motivo = $_POST['motivo'] ?? '';

    if(empty($fecha)) $mensajeErrorFecha = "Seleccione una fecha válida.";
    if(empty($hora)) $mensajeErrorHora = "Seleccione una hora válida.";
    if($estado != "Confirmada" && $estado != "Pendiente" && $estado != "Cancelada" && $estado != "Programada") 
        $mensajeErrorEstado = "Seleccione un estado válido.";
    if(strlen($motivo) < 5) $mensajeErrorMotivo = "El motivo debe tener al menos 5 caracteres.";

    if(empty($mensajeErrorFecha) && empty($mensajeErrorHora) && empty($mensajeErrorEstado) && empty($mensajeErrorMotivo)){
        $stmt = $mysqli->prepare("UPDATE CITAS SET fecha = ?, hora = ?, estado = ?, motivo = ? WHERE id_cita = ?");
        $stmt->bind_param("ssssi", $fecha, $hora, $estado, $motivo, $id);

        if ($stmt->execute()){
            cerrarConexion($mysqli);
            echo '<script>
                    alert("La cita se actualizo correctamente.");
                    window.location.href = "citas.php";
                  </script>';
            exit();
        } else {
            throw new exception("Sucedio un error al actualizar la cita.");
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Modificar Cita| MotoRepuestos Rojas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .card-sombra { box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .card-header-custom { background-color: #8B0000; color: white; font-size: 1.5rem; font-weight: bold; }
    .btn-custom { background-color: #8B0000; color: #fff; }
    .btn-custom:hover { background-color: #a52a2a; }
  </style>
</head>

<body class="bg-light">
  <?php include '../../componentes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card card-sombra">
      <div class="card-header card-header-custom">Modificar Cita</div>
      <div class="card-body">

        <form method="POST">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="fechaCita" class="form-label">Fecha</label>
              <input type="date" class="form-control <?= !empty($mensajeErrorFecha) ? 'is-invalid' : '' ?>" id="fecha" name="fecha" min="<?= date('Y-m-d') ?>" value="<?= $fecha ?>">
              <div class="invalid-feedback"><?= $mensajeErrorFecha ?></div>
            </div>

            <div class="col-md-6 mb-3">
              <label for="horaCita" class="form-label">Hora</label>
              <input type="time" class="form-control <?= !empty($mensajeErrorHora) ? 'is-invalid' : '' ?>" name="hora" value="<?= $hora ?>">
              <div class="invalid-feedback"><?= $mensajeErrorHora ?></div>
            </div>
          </div>

          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select <?= !empty($mensajeErrorEstado) ? 'is-invalid' : '' ?>" name="estado">
                <option value="Confirmada" <?= ($estado=="Confirmada") ? 'selected' : '' ?>>Confirmada</option>
                <option value="Pendiente" <?= ($estado=="Pendiente") ? 'selected' : '' ?>>Pendiente</option>
                <option value="Cancelada" <?= ($estado=="Cancelada") ? 'selected' : '' ?>>Cancelada</option>
                <option value="Programada" <?= ($estado=="Programada") ? 'selected' : '' ?>>Programada</option>
            </select>
            <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
          </div>
         
          <div class="mb-3">
            <label for="motivoCita" class="form-label">Motivo</label>
            <textarea class="form-control <?= !empty($mensajeErrorMotivo) ? 'is-invalid' : '' ?>" name="motivo" rows="3"><?= $motivo ?></textarea>
            <div class="invalid-feedback"><?= $mensajeErrorMotivo ?></div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-custom">Guardar Cambios</button>
            <a class="btn btn-secondary" href="citas.php">Cancelar</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</body>
</html>