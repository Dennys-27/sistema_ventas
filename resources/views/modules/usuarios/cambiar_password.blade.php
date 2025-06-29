<form id="frmPassword" onsubmit="return cambiar_password()">
    <div class="modal fade" id="cambiar_password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="id_usuario" name="id_usuario" hidden>
                    <label for="password">Password nuevo</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Ingrese el nuevo password">
                </div>
                <div class="modal-footer">
                    <span class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</span>
                    <button  class="btn btn-warning text-white">Actualizar password</button>

                </div>
            </div>
        </div>
    </div>
</form>
