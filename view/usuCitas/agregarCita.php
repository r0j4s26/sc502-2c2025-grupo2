
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../componentes/navbar.php'; ?> 
  <div class="modal fade" id="modalAgregarCita" >
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header text-white" style="background-color:#8B0000;">
        <h5 class="modal-title" id="modalAgregarCitaLabel">Agregar Nueva Cita</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="fechaCita" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fechaCita">
          </div>
          <div class="mb-3">
            <label for="horaCita" class="form-label">Hora</label>
            <input type="time" class="form-control" id="horaCita">
          </div>
          <div class="mb-3">
                <label for="motivoCita" class="form-label">Motivo</label>
                <textarea class="form-control" id="motivoCita" rows="3"></textarea>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success">Guardar Cita</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>

