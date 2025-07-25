<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Citas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <?php include '../componentes/navbar.php'; ?>
    <div class="container mt-5">

    <h2 class="mb-4 text-center">Lista de Citas</h2>

    <div class="card mb-3 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

        <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
            <div><strong>Fecha:</strong> 2025-07-12</div>
            <div><strong>Hora:</strong> 10:30 AM</div>
            <div><strong>Estado:</strong> <span class="badge bg-success">Confirmada</span></div>
            <div><strong>Motivo:</strong> Pintura de parachoques</div>
        </div>

        <div class="mt-3 mt-md-0">
            <button class="btn btn-sm btn-primary me-2">Modificar</button>
            <button class="btn btn-sm btn-danger">Eliminar</button>
        </div>

        </div>
    </div>

    <div class="card mb-3 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

        <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
            <div><strong>Fecha:</strong> 2025-07-14</div>
            <div><strong>Hora:</strong> 2:00 PM</div>
            <div><strong>Estado:</strong> <span class="badge bg-warning text-dark">Pendiente</span></div>
            <div><strong>Motivo:</strong> Cambio de guardabarros</div>
        </div>

        <div class="mt-3 mt-md-0">
            <button class="btn btn-sm btn-primary me-2">Modificar</button>
            <button class="btn btn-sm btn-danger">Eliminar</button>
        </div>

        </div>
    </div>

    </div>

  <?php include '../componentes/navbar.php'; ?>

  <div class="container mt-5">
    <h2 class="mb-4 text-center">Lista de Citas</h2>

    <div class="card mb-3 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
          <div><strong>Fecha:</strong> 2025-07-12</div>
          <div><strong>Hora:</strong> 10:30 AM</div>
          <div><strong>Estado:</strong> <span class="badge bg-success">Confirmada</span></div>
          <div><strong>Motivo:</strong> Pintura de parachoques</div>
        </div>
        <div class="mt-3 mt-md-0">
          <button class="btn btn-sm btn-danger">Eliminar</button>
        </div>
      </div>
    </div>

    <div class="card mb-3 shadow-sm">
      <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
          <div><strong>Fecha:</strong> 2025-07-14</div>
          <div><strong>Hora:</strong> 2:00 PM</div>
          <div><strong>Estado:</strong> <span class="badge bg-warning text-dark">Pendiente</span></div>
          <div><strong>Motivo:</strong> Cambio de guardabarros</div>
        </div>
        <div class="mt-3 mt-md-0">
          <button class="btn btn-sm btn-danger">Eliminar</button>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-center my-4">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCita">
        <i class="fas fa-plus"></i> Agregar Cita
    </button>
    </div>

  </div>

    <?php include 'agregarCita.php'; ?>

</body>
</html>