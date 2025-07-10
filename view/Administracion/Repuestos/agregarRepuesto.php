<div class="modal fade" id="modalNuevoRepuesto" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#8B0000;">
        <h5 class="modal-title text-white" id="modalNuevoRepuestoLabel">Registrar Nuevo Repuesto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevoRepuesto" method="POST" action="procesarNuevoRepuesto.php">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" required>
          </div>
          <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <input type="text" class="form-control" id="categoria" name="categoria" required>
          </div>
          <div class="mb-3">
            <label for="costoUnitario" class="form-label">Costo Unitario</label>
            <input type="number" step="0.01" class="form-control" id="costoUnitario" name="costoUnitario" required>
          </div>
          <div class="mb-3">
            <label for="precioVenta" class="form-label">Precio Venta</label>
            <input type="number" step="0.01" class="form-control" id="precioVenta" name="precioVenta" required>
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="" selected disabled>Seleccione estado</option>
              <option value="Nuevo">Activo</option>
              <option value="Usado">Inactivo</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formNuevoRepuesto" class="btn btn-success">Guardar</button>
      </div>
    </div>
  </div>
</div>