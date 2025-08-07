<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cita</title>
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
        <div class="card-header card-header-custom">
            Modificar Cita
        </div>
        <div class="card-body">
            <form>

            <div class="row">
                <div class="col-md-6 mb-3">
                <label for="fechaCita" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fechaCita">
                </div>
                <div class="col-md-6 mb-3">
                <label for="horaCita" class="form-label">Hora</label>
                <input type="time" class="form-control" id="horaCita">
                </div>
            </div>

            <div class="mb-3">
                <label for="estadoCita" class="form-label">Estado</label>
                <select class="form-select" id="estadoCita">
                <option value="Confirmada">Confirmada</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Cancelada">Cancelada</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="motivoCita" class="form-label">Motivo</label>
                <textarea class="form-control" id="motivoCita" rows="3" placeholder="Describa el motivo de la cita..."></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                <a href="/sc502-2c2025-grupo2/view/Administracion/Citas/citas.php" class="btn btn-secondary">Cancelar</a>
            </div>

            </form>
        </div>
        </div>
  </div>
</body>
</html>