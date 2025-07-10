<div class="modal fade" id="modalAgregarCategoria" >
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header " style="background-color:#8B0000;">
          <h5 class="modal-title text-white" id="modalAgregarCategoriaLabel">Agregar Nueva Categoría</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label for="nombreCategoria" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombreCategoria" required>
          </div>
          <div class="mb-3">
              <label for="descripcionCategoria" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcionCategoria" rows="3" required></textarea>
          </div>
          <div class="mb-3">
              <label for="estadoCategoria" class="form-label">Estado</label>
              <select class="form-select" id="estadoCategoria" required>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
              </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>