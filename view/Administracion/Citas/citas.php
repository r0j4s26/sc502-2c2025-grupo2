<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de citas</title>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tabla de Citas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<body class="bg-light">
  <?php include '../../componentes/navbar.php'; ?>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Listado de Citas</h2>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th scope="col">Fecha</th>
            <th scope="col">Hora</th>
            <th scope="col">Estado</th>
            <th scope="col">Motivo</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr>
            <td>2025-07-12</td>
            <td>10:30 AM</td>
            <td><span class="badge bg-success">Confirmada</span></td>
            <td>Pintura de parachoques</td>
            <td>
              <a href="modificarCita.php" class="btn btn-sm btn-primary me-2">Modificar</a>
              <button class="btn btn-sm btn-danger">Eliminar</button>
            </td>
          </tr>
          <tr>
            <td>2025-07-14</td>
            <td>2:00 PM</td>
            <td><span class="badge bg-warning text-dark">Pendiente</span></td>
            <td>Cambio de guardabarros</td>
            <td>
              <a href="modificarCita.php" class="btn btn-sm btn-primary me-2">Modificar</a>
              <button class="btn btn-sm btn-danger">Eliminar</button>
            </td>
          </tr>
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