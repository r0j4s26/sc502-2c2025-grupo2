<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$mensajeExito = "";
$mensajeErrorNombre = "";
$mensajeErrorTelefono = "";
$mensajeErrorCorreo = "";
$mensajeErrorDireccion = "";
$mensajeErrorMetodo = "";
$mensajeErrorEstado = "";
$proveedor = $mysqli->query("SELECT * FROM PROVEEDORES WHERE id_proveedor = $id")->fetch_assoc();
$metodosPago = ["Contado", "Credito"];
$nombre = $proveedor['nombre'];
$telefono = $proveedor['telefono'];
$correo = $proveedor['correo'];
$direccion = $proveedor['direccion'];
$metodo_pago = $proveedor['metodo_pago'];
$estado = ($proveedor['estado'] == 1) ? "Activo" : "Inactivo";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST["nombre"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $direccion = trim($_POST["direccion"]);
    $metodo_pago = $_POST["metodo_pago"];
    $estado = $_POST["estado"];
    if(strlen($nombre) < 3 || strlen($nombre) > 50) $mensajeErrorNombre = "El nombre debe tener entre 3 y 50 caracteres.";
    if(!preg_match('/^\d{8}$/', $telefono)) $mensajeErrorTelefono = "El teléfono debe tener 8 dígitos.";
    if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) $mensajeErrorCorreo = "Correo no válido.";
    if(strlen($direccion) < 5) $mensajeErrorDireccion = "La dirección es demasiado corta.";
    if(!in_array($metodo_pago, $metodosPago)) $mensajeErrorMetodo = "Debe seleccionar un método de pago válido.";
    if($estado !== "Activo" && $estado !== "Inactivo") $mensajeErrorEstado = "Debe seleccionar un estado válido.";
    if(empty($mensajeErrorNombre) && empty($mensajeErrorTelefono) && empty($mensajeErrorCorreo) &&
       empty($mensajeErrorDireccion) && empty($mensajeErrorMetodo) && empty($mensajeErrorEstado)) {

        $estadoBit = ($estado === "Activo") ? 1 : 0;
        $stmt = $mysqli->prepare("UPDATE PROVEEDORES SET nombre=?, telefono=?, correo=?, direccion=?, metodo_pago=?, estado=? WHERE id_proveedor=?");
        $stmt->bind_param("sisssii", $nombre, $telefono, $correo, $direccion, $metodo_pago, $estadoBit, $id);

        if($stmt->execute()){
            $mensajeExito = "¡Proveedor actualizado correctamente!";
        } else {
            $mensajeErrorNombre = "Ocurrió un error al actualizar el proveedor.";
        }
    }
}

cerrarConexion($mysqli);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Proveedor | MotoRepuestos Rojas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card-sombra { box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .card-header-custom { background-color: #8B0000; color: white; font-weight: bold; }
        .btn-custom { background-color: #8B0000; color: white; }
        .btn-custom:hover { background-color: #a52a2a; }
    </style>
</head>
<body class="bg-light">
<?php include '../../componentes/navbar.php'; ?>

<div class="container mt-5">
    <div class="card card-sombra">
        <div class="card-header card-header-custom text-center">
            Modificar Proveedor
        </div>
        <div class="card-body">
            <?php if(!empty($mensajeExito)): ?>
                <div class="alert alert-success text-center"><?= $mensajeExito ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?= $nombre ?>">
                        <?php if(!empty($mensajeErrorNombre)): ?>
                            <div class="alert alert-danger mt-2"><?= $mensajeErrorNombre ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="number" class="form-control" name="telefono" value="<?= $telefono ?>">
                        <?php if(!empty($mensajeErrorTelefono)): ?>
                            <div class="alert alert-danger mt-2"><?= $mensajeErrorTelefono ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" value="<?= $correo ?>">
                        <?php if(!empty($mensajeErrorCorreo)): ?>
                            <div class="alert alert-danger mt-2"><?= $mensajeErrorCorreo ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" value="<?= $direccion ?>">
                        <?php if(!empty($mensajeErrorDireccion)): ?>
                            <div class="alert alert-danger mt-2"><?= $mensajeErrorDireccion ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Método de pago</label>
                        <select class="form-select" name="metodo_pago">
                            <option value="">Seleccione un método</option>
                            <?php foreach($metodosPago as $metodo): ?>
                                <option value="<?= $metodo ?>" <?= ($metodo_pago == $metodo) ? 'selected' : '' ?>><?= $metodo ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(!empty($mensajeErrorMetodo)): ?>
                            <div class="alert alert-danger mt-2"><?= $mensajeErrorMetodo ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <option value="">Seleccione un estado</option>
                            <option value="Activo" <?= ($estado == "Activo") ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= ($estado == "Inactivo") ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                        <?php if(!empty($mensajeErrorEstado)): ?>
                            <div class="alert alert-danger mt-2"><?= $mensajeErrorEstado ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-custom">Guardar</button>
                    <a href="proveedores.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>