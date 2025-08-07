<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

$id = $_GET['id'];

$cita = $mysqli->query("SELECT * FROM CITAS WHERE id_cita = $id")->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){

  $stmt = $mysqli->prepare("UPDATE CITAS SET fecha = ?, hora = ?, estado = ?, motivo = ? WHERE id_cita = ?");
  $stmt->bind_param("ssssi", $_POST['fecha'], $_POST['hora'], $_POST['estado'], $_POST['motivo'], $id);

  if ($stmt->execute()){

    cerrarConexion($mysqli);

    echo '<script>
            alert("La cita se actualizo correctamente.")
            window.location.href = "citas.php"
            </script>';

  } else {
    throw new exception("Sucedio un error al actualizar la cita.");
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
    .card-sombra {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .card-header-custom {
      background-color: #8B0000;
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .btn-custom {
      background-color: #8B0000;
      color: #fff;
    }

    .btn-custom:hover {
      background-color: #a52a2a;
    }
  </style>
</head>

<body class="bg-light">

  <?php include '../../componentes/navbar.php'; ?>

  <div class="container mt-5">
    <div class="card card-sombra">
      <div class="card-header card-header-custom">Modificar Cita</div>
      <div class="card-body">

        <form id="modificaCitaForm" method="POST" action="">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="fechaCita" class="form-label">Fecha</label>
              <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $cita["fecha"] ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="horaCita" class="form-label">Hora</label>
              <input type="time" class="form-control" id="hora" name="hora" value="<?php echo $cita["hora"] ?>"
                required>
            </div>
          </div>

          <div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select class="form-select" id="estado" name="estado">
        <option value="">--Seleccione un estado--</option>
        <option value="Confirmada" <?php if ($cita["estado"] == "Confirmada") echo 'selected'; ?>>Confirmada</option>
        <option value="Pendiente" <?php if ($cita["estado"] == "Pendiente") echo 'selected'; ?>>Pendiente</option>
        <option value="Cancelada" <?php if ($cita["estado"] == "Cancelada") echo 'selected'; ?>>Cancelada</option>
    </select>
</div>
         
          <div class="mb-3">
            <label for="motivoCita" class="form-label">Motivo</label>
            <textarea class="form-control" id="motivo" name="motivo" rows="3"
              placeholder="Describa el motivo de la cita..." required><?php echo $cita["motivo"]; ?></textarea>
          </div>

          <div class="text-end">
            <button type="submit" href="citas.php" class="btn btn-custom"><strong>Guardar Cambios</strong></button>
            <a class="btn btn-secondary" href="citas.php"><strong>Cancelar</strong></a>
          </div>

        </form>
      </div>
    </div>
  </div>
</body>

</html>