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