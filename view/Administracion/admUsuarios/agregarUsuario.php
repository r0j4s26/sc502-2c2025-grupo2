<div class="modal fade" id="modalAgregarUsuario" >
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header text-white" style="background-color:#8B0000;">
          <h5 class="modal-title text-white" id="modalAgregarUsuarioLabel">Agregar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nombreUsuario" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombreUsuario" required>
            </div>
            <div class="mb-3">
                <label for="apellidosUsuario" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidosUsuario" required>
            </div>
            <div class="mb-3">
                <label for="telefonoUsuario" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefonoUsuario" required>
            </div>
            <div class="mb-3">
                <label for="emailUsuario" class="form-label">Email</label>
                <input type="email" class="form-control" id="emailUsuario" required>
            </div>
            <div class="mb-3">
                <label for="estadoUsuario" class="form-label">Estado</label>
                <select class="form-select" id="estadoUsuario" required>
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