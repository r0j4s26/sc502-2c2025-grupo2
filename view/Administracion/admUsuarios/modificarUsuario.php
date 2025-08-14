<!DOCTYPE html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../componentes/comprobarInicio.php';

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli2 = abrirConexion();

$id = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

$mensajeExito = "";
$mensajeErrorNombre = "";
$mensajeErrorApellidos = "";
$mensajeErrorTelefono = "";
$mensajeErrorEmail = "";
$mensajeErrorEstado = "";
$mensajeError = "";

$clienteID = $mysqli2->query("SELECT * FROM USUARIOS WHERE id_cliente = $id")->fetch_assoc();
cerrarConexion($mysqli2);

$enviado = isset($_POST['submitModificarUsuario']);

if($enviado){
    $nombre = trim($_POST['nombreCliente'] ?? '');
    $apellidos = trim($_POST['apellidosCliente'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $estado = $_POST['estadoCliente'] ?? '';
    if(strlen($nombre) < 3) $mensajeErrorNombre = "El nombre debe tener al menos 3 caracteres.";
    if(strlen($apellidos) < 3) $mensajeErrorApellidos = "Los apellidos deben tener al menos 3 caracteres.";
    if(!preg_match('/^[0-9]{8,15}$/', $telefono)) $mensajeErrorTelefono = "El teléfono debe tener solo números y entre 8 y 15 dígitos.";
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $mensajeErrorEmail = "Ingrese un correo válido.";
    if($estado !== "Activo" && $estado !== "Inactivo") $mensajeErrorEstado = "Seleccione un estado válido.";
    if(empty($mensajeErrorNombre) && empty($mensajeErrorApellidos) && empty($mensajeErrorTelefono) && empty($mensajeErrorEmail) && empty($mensajeErrorEstado)){
        try {
            $mysqli = abrirConexion();
            $idEstado = ($estado == "Activo") ? 1 : 0;
            $stmt = $mysqli->prepare("UPDATE USUARIOS SET nombre=?, apellidos=?, telefono=?, email=?, estado=? WHERE id_cliente=?");
            $stmt->bind_param("ssssii", $nombre, $apellidos, $telefono, $email, $idEstado, $id);
            if($stmt->execute()){
                header("Location: usuarios.php?modificado=1");
                exit();
            } else {
                $mensajeError = "Error al actualizar el usuario: ".$stmt->error;
            }
            cerrarConexion($mysqli);
        } catch(Exception $e){
            $mensajeError = "Ocurrió un error: ".$e->getMessage();
        }
    }
}
?>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Usuario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<style>
.card-sombra { box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.card-header-custom { background-color: #8B0000; color: white; font-size: 1.5rem; font-weight: bold; }
.btn-custom { background-color: #8B0000; color: #fff; }
.btn-custom:hover { background-color: #a52a2a; }
</style>
</head>
<body class="bg-light">
<?php include '../../componentes/navbar.php'; ?>
<div class="container mt-5">
    <div class="card card-sombra">
        <div class="card-header card-header-custom">Modificar Usuario</div>
        <div class="card-body">

        <?php if(!empty($mensajeError)): ?>
            <div class="alert alert-danger"><?= $mensajeError ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombreCliente" class="form-control <?= !empty($mensajeErrorNombre) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($enviado ? $nombre : $clienteID['nombre']) ?>">
                <?php if(!empty($mensajeErrorNombre)): ?>
                    <div class="invalid-feedback"><?= $mensajeErrorNombre ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidosCliente" class="form-control <?= !empty($mensajeErrorApellidos) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($enviado ? $apellidos : $clienteID['apellidos']) ?>">
                <?php if(!empty($mensajeErrorApellidos)): ?>
                    <div class="invalid-feedback"><?= $mensajeErrorApellidos ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control <?= !empty($mensajeErrorTelefono) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($enviado ? $telefono : $clienteID['telefono']) ?>">
                <?php if(!empty($mensajeErrorTelefono)): ?>
                    <div class="invalid-feedback"><?= $mensajeErrorTelefono ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control <?= !empty($mensajeErrorEmail) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($enviado ? $email : $clienteID['email']) ?>">
                <?php if(!empty($mensajeErrorEmail)): ?>
                    <div class="invalid-feedback"><?= $mensajeErrorEmail ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="estadoCliente" class="form-select <?= !empty($mensajeErrorEstado) ? 'is-invalid' : '' ?>">
                    <option value="Activo" <?= (($enviado ? $estado : $clienteID['estado']==1) == 'Activo') ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= (($enviado ? $estado : $clienteID['estado']==0) == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                </select>
                <?php if(!empty($mensajeErrorEstado)): ?>
                    <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
                <?php endif; ?>
            </div>

            <div class="text-end">
                <button type="submit" name="submitModificarUsuario" class="btn btn-custom">Guardar Cambios</button>
                <a href="/sc502-2c2025-grupo2/view/Administracion/admUsuarios/usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
        </div>
    </div>
</div>
</body>
</html>