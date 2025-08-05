<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $stmt = $mysqli->prepare("INSERT INTO PROVEEDORES (nombre, telefono, correo, direccion, metodo_pago, estado)
        VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("sisssi", $_POST["nombre"], $_POST["telefono"], $_POST["correo"], $_POST["direccion"], $_POST["metodo_pago"], $_POST["estado"]);

        if ($stmt->execute()) {

            cerrarConexion($mysqli);

            echo '<script>
            alert("El usuario se insertó correctamente.")
            window.location.href = "proveedores.php"
            </script>';
        } else {
            throw new exception("Sucedio un error al agregar proveedor.");
        }
    }
} catch (Exception $e) {

    echo '<script> alert("Sucedio un error al agregar el proveedor.") </script>';
    echo '<script> console.log(" ' . $e->getMessage() . ' ") </script>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agregar Proveedor | MotoRepuestos Rojas</title>
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
                            <h4 class="fw-bold mt-2">Agregar Proveedor</h4>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <form id="registroForm" method="post" action="">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombreProveedor" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" maxlength="50" id="nombre" name="nombre"
                                        placeholder="Ingresar nombre" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="telefonoProveedor" class="form-label">Teléfono</label>
                                    <input type="number" class="form-control" minlenght="8" maxlength="8" id="telefono"
                                        name="telefono" placeholder="Ingresar teléfono" required />
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="correoProveedor" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" maxlength="50" id="correo" name="correo"
                                        placeholder="Ingresar Correo" required />
                                </div>
                                <div class="col-md-6">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" maxlength="100" id="direccion"
                                        name="direccion" placeholder="Ingresar Dirección" required />
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="metodoPago" class="form-label">Método de pago</label>
                                    <select class="form-select" id="metodo_pago" name="metodo_pago"
                                        aria-label="Default select example">
                                        <option select>--Seleccione un método--</option>
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

                            <div>
                                <button class="btn btn-primary w-100 py-2 mt-2" type="submit"><strong>Registrar</strong></button>
                                <a class="btn btn-danger w-100 py-2 mt-1" href="proveedores.php"><strong>Cancelar</strong></a> 
                            </div>

                        </form>
                    </div>

                </div>              
            </div>
        </div>
    </div>
</body>
</html>