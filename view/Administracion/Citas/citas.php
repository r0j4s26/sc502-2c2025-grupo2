<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../../accessoDatos/accesoDatos.php';

if(!isset($_SESSION["nombreUsuario"])){
    echo '<script> 
        alert("Debe iniciar sesión para acceder a esta página.") 
        window.location.href = "login.php"
        </script>';
}

$mysqli = abrirConexion();

$citas = $mysqli->query("SELECT * from CITAS");

if(!$citas){
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>

<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tabla de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
  <?php include '../../componentes/navbar.php'; ?>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Listado de Citas</h2>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>Id Cita</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Motivo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php while ($fila = $citas->fetch_assoc()):?>
            <tr>
                <td><?= $fila['id_cita']?></td>
                <td><?= $fila['fecha']?></td>
                <td><?= $fila['hora']?></td>
                <td><span class="badge bg-success"><?= $fila['estado']?></span></td>
                <td><?= $fila['motivo']?></td>
                <td>
                  <a href="eliminarUsuario.php?id_cliente=<?php echo $fila['id_cita']?>" class="btn btn-danger btn-sm">Eliminar</a>
                  <a href="modificarUsuario.php?id_cliente=<?php echo $fila['id_cita']?>" class="btn btn-primary btn-sm">Modificar</a>
                </td>
            </tr>
          <?php endwhile;?>
        </tbody>
      </table>
    </div>
  </div>
      <div class="d-flex justify-content-center my-4">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCita">
        <i class="fas fa-plus"></i> Agregar Cita
    </button>

    </div>
    <?php include '../../usuCitas/agregarCita.php'; ?>


</body>
</html>