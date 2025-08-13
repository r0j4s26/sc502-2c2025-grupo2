<!DOCTYPE html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting (E_ALL);
require_once '../../../accessoDatos/accesoDatos.php';
$mysqli2 = abrirConexion();

$id = $_GET['id_cliente'];
$mensajeExito = '';
$errorNombre = $errorDescripcion = $errorUrl = $errorEstado = "";

$clienteID = $mysqli2->query("SELECT * FROM USUARIOS WHERE id_cliente = $id")->fetch_assoc();

cerrarConexion($mysqli2);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $mysqli = abrirConexion();
        $errores = 0;
        if ($errores == 0){
            $stmt = $mysqli->prepare("UPDATE USUARIOS SET nombre = ?, apellidos = ?, telefono =?, email =?, estado =? WHERE id_cliente = ?");
            $estadoCliente = $_POST['estadoCliente'];
            $idEstado = ($estadoCliente == "Activo") ? 1 : 0;
            $stmt->bind_param("ssssii", $_POST['nombreCliente'], $_POST['apellidosCliente'], $_POST['telefono'], $_POST['email'],$idEstado, $id);
        if ($stmt->execute()) {
            $clienteID = $mysqli->query("SELECT * FROM USUARIOS WHERE id_cliente = $id")->fetch_assoc();
            cerrarConexion($mysqli);
            $mensajeExito = "¡Cliente actualizado correctamente! Redirigiendo...";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '/sc502-2c2025-grupo2/view/Administracion/admUsuarios/usuarios.php';
                }, 2500);
            </script>";
        }else {
                throw new Exception("Sucedió un error al realizar la actualización de la tarea.");
            }
        }else {

        }
    } catch (Exception $e) {
         echo "Error: " . $e->getMessage();
    }
}
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
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
                Modificar Usuario
            </div>
            <div class="card-body">
                <?php if (!empty($mensajeExito)): ?>
                    <div class="alert alert-success">
                        <?php echo $mensajeExito; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombreCliente" value="<?php echo $clienteID['nombre'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidosCliente" value="<?php echo $clienteID['apellidos'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $clienteID['telefono'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $clienteID['email'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estadoCliente" required>
                            <option value="Activo" <?php if ($clienteID["estado"] == 1) echo 'selected'; ?>>Activo</option>
                            <option value="Inactivo" <?php if ($clienteID["estado"] == 0) echo 'selected'; ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                        <a href="/sc502-2c2025-grupo2/view/Administracion/admUsuarios/usuarios.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>