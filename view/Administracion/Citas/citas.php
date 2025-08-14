<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
if (!isset($_SESSION["nombreUsuario"])) {
    echo '<script>
        alert("Debe iniciar sesión para acceder a esta página.");
        window.location.href = "/sc502-2c2025-grupo2/view/usuarios/index.php";
    </script>';
    exit();
}

$mysqli = abrirConexion();

$sql = "SELECT c.*, CONCAT(cl.nombre, ' ', cl.apellidos) AS nombre_completo 
        FROM CITAS c
        INNER JOIN USUARIOS cl ON c.id_cliente = cl.id_cliente
        ORDER BY c.fecha";

$resultado = $mysqli->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas | MotoRepuestos Rojas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

  <?php include '../../componentes/navbar.php'; ?>

  <div class="container mt-5">
    <h2 class="mb-4 text-center">Lista de Citas</h2>
    <div class="d-flex justify-content-center mb-4">
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAgregarCita">
            <i class="fas fa-plus"></i> Agendar cita
        </button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-danger text-center">
          <tr>
            <th>Id Cita</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Motivo</th>
            <th>Nombre Cliente</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php while ($u = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= $u['id_cita'] ?></td>
              <td><?= $u['fecha'] ?></td>
              <td><?= $u['hora'] ?></td>
              <td><?= $u['estado'] ?></td>
              <td><?= $u['motivo'] ?></td>
              <td><?= $u['nombre_completo'] ?></td>
              <td>
                <a href="eliminarCitas.php?id=<?= $u['id_cita'] ?>" onclick="return confirm('Desea eliminar la cita?')" class="btn btn-sm btn-danger">Eliminar</a>
                <a href="modificarCitas.php?id=<?= $u['id_cita'] ?>" class="btn btn-sm btn-primary me-2">Modificar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php include 'agregarCitas.php'; ?>
  </div>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function () {
        $('table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
  </script>
</body>
</html>