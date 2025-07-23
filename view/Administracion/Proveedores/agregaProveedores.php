<?php


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agrega Proveedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg bg-light">
    <?php include '../../componentes/navbar.php'; ?>

    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow rounded-4 border-0">

                    <div class="card-header bg-white text-center border-0 pb-0">
                        <div stye="background-color: #8B0000;">
                        <h4 class="fw-bold mt-2">Agrega un Proveedor</h4>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <form id="registroForm" method="post" action="">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombreProveedor" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" maxlength="50" id="txtNombreProveedor"
                                        name="txtNombreProveedor" placeholder="" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="telefonoProveedor" class="form-label">Teléfono</label>
                                    <input type="number" class="form-control" minlenght="8" maxlength="8"
                                        id="telefonoProveedor" name="telefonoProveedor" placeholder="" required />
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="correoProveedor" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" maxlength="50" id="txtEmail"
                                        name="txtEmail" placeholder="" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" maxlength="100" id="txtDireccion"
                                        name="txtDireccion" placeholder="" required />
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                <label for="metodoPago" class="form-label">Método de pago</label>
                                <select class="form-select" id="metodoPago" name="metodoPago"
                                    aria-label="Default select example">
                                    <option select>Seleccione un método</option>
                                    <option select>Contado</option>
                                    <option select>Credito</option>
                                </select>
                                </div>
                                <div class="col-md-6">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado"
                                    aria-label="Default select example">
                                    <option select>Seleccione un estado</option>
                                    <option select>Activo</option>
                                    <option select>Inactivo</option>
                                </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 py-2 mt-2"><strong>Registrar</strong></button>

                            <button type="submit" class="btn btn-secondary w-100 py-2 mt-1"><strong>Regresar</strong></button>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
</body>




</html>