<!DOCTYPE html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../componentes/comprobarInicio.php';
require_once '../../../accessoDatos/accesoDatos.php';
$mysqli2 = abrirConexion();

$id = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
 
$mensajeExito = "";
$mensajeErrorNombre = "";
$mensajeErrorDescripcion = "";
$mensajeErrorMarca = "";
$mensajeErrorCategoria = "";
$mensajeErrorCosto = "";
$mensajeErrorPrecio = "";
$mensajeErrorEstado = "";
$mensajeErrorProveedor = "";
$mensajeErrorStock = "";
$mensajeError = "";

$nombre = "";
$descripcion = "";
$marca = "";
$categoria = "";
$costoUnitario = "";
$precioVenta = "";
$estado = "";
$proveedor = "";
$stock = "";

$marcas = ["Honda", "Nissan", "Toyota", "Mazda", "Ford", "Chevrolet", "Kia", "Hyundai",
           "Volkswagen", "Mitsubishi", "Suzuki", "Renault", "Fiat", "Peugeot", "Citroën", "Chery", "Geely"];

$repuestoID = $mysqli2->query("
    SELECT 
        P.nombre AS nom,
        P.descripcion AS descri,
        P.marca AS marca,
        P.costo_unitario AS costoU,
        P.precio_venta AS precioVenta,
        P.estado AS estado,
        IFNULL(P.id_proveedor, 0) AS idproveedor,
        IFNULL(P.id_categoria, 0) AS idCategoria,
        IFNULL(PR.nombre, '0') AS proveedor,
        IFNULL(C.nombre, '0') AS categoria,
        IFNULL(P.stock,0) AS stock
    FROM PRODUCTOS P
    LEFT JOIN PROVEEDORES PR ON P.id_proveedor = PR.id_proveedor
    LEFT JOIN CATEGORIAS C ON P.id_categoria = C.id_categoria
    WHERE P.id_producto = $id
")->fetch_assoc();

$nombre = $repuestoID["nom"];
$descripcion = $repuestoID["descri"];
$marca = $repuestoID["marca"];
$categoria = $repuestoID["idCategoria"];
$costoUnitario = $repuestoID["costoU"];
$precioVenta = $repuestoID["precioVenta"];
$estado = ($repuestoID["estado"] == 1) ? "Activo" : "Inactivo";
$proveedor = $repuestoID["idproveedor"];
$stock = $repuestoID["stock"];

$proveedores = [];
$resultado1 = $mysqli2->query("SELECT id_proveedor, nombre FROM PROVEEDORES WHERE estado = 1");
while ($row = $resultado1->fetch_assoc()) {
    $proveedores[$row['id_proveedor']] = $row['nombre'];
}

$categorias = [];
$resultado2 = $mysqli2->query("SELECT id_categoria, nombre FROM CATEGORIAS WHERE estado = 1");
while ($row = $resultado2->fetch_assoc()) {
    $categorias[$row['id_categoria']] = $row['nombre'];
}

cerrarConexion($mysqli2);

$enviado = isset($_POST['submitModificarRepuesto']);

if($enviado){
    try {
        $nombre = trim($_POST['nombreRepuesto'] ?? '');
        $descripcion = trim($_POST['descripcionRepuesto'] ?? '');
        $marca = $_POST['marcaRepuesto'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $costoUnitario = trim($_POST['costoUnitario'] ?? '');
        $precioVenta = trim($_POST['precioVenta'] ?? '');
        $estado = $_POST['estadoRepuesto'] ?? '';
        $proveedor = $_POST['proveedor'] ?? '';
        $stock = trim($_POST['stock'] ?? '');

        if(strlen($nombre) < 3 || strlen($nombre) > 100) $mensajeErrorNombre = "El nombre debe contener entre 3 y 100 caracteres.";
        if(strlen($descripcion) < 5) $mensajeErrorDescripcion = "La descripción es demasiado corta.";
        if(!in_array($marca, $marcas)) $mensajeErrorMarca = "Debe seleccionar una marca válida.";
        if(!array_key_exists($categoria, $categorias)) $mensajeErrorCategoria = "Debe seleccionar una categoría válida.";
        if(!is_numeric($costoUnitario) || floatval($costoUnitario) <= 0) $mensajeErrorCosto = "El costo unitario debe ser un número mayor a 0.";
        if(!is_numeric($precioVenta) || floatval($precioVenta) <= 0) $mensajeErrorPrecio = "El precio de venta debe ser un número mayor a 0.";
        if($estado !== "Activo" && $estado !== "Inactivo") $mensajeErrorEstado = "Debe seleccionar un estado válido.";
        if(!array_key_exists($proveedor, $proveedores)) $mensajeErrorProveedor = "Debe seleccionar un proveedor válido.";
        if(!is_numeric($stock) || intval($stock) < 0) $mensajeErrorStock = "El stock debe ser un número entero mayor o igual a 0.";

        if(empty($mensajeErrorNombre) && empty($mensajeErrorDescripcion) && empty($mensajeErrorMarca) &&
           empty($mensajeErrorCategoria) && empty($mensajeErrorCosto) && empty($mensajeErrorPrecio) &&
           empty($mensajeErrorEstado) && empty($mensajeErrorProveedor) && empty($mensajeErrorStock)) {

            $mysqli = abrirConexion();
            $idEstado = ($estado == "Activo") ? 1 : 0;
            $stmt = $mysqli->prepare("UPDATE PRODUCTOS 
                SET nombre = ?, descripcion = ?, marca = ?, costo_unitario = ?, precio_venta = ?, estado = ?, id_proveedor = ?, id_categoria = ?, stock = ?
                WHERE id_producto = ?");
            $stmt->bind_param(
                "sssdidiiii", 
                $nombre, $descripcion, $marca, $costoUnitario, $precioVenta, $idEstado, $proveedor, $categoria, $stock, $id
            );

            if($stmt->execute()){
                cerrarConexion($mysqli);
                header("Location: repuestos.php?modificado=1");
                exit();
            } else {
                $mensajeError = "Error al actualizar el repuesto: " . $stmt->error;
            }

            cerrarConexion($mysqli);
        }

    } catch(Exception $e){
        $mensajeError = "Ocurrió un error: " . $e->getMessage();
    }
}
?>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Repuesto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <div class="card-header card-header-custom">Modificar Repuesto</div>
        <div class="card-body">
            <?php if(!empty($mensajeError)): ?>
                <div class="alert alert-danger"><?= $mensajeError ?></div>
            <?php endif; ?>
            <?php if(!empty($mensajeExito)): ?>
                <div class="alert alert-success"><?= $mensajeExito ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre del repuesto</label>
                    <input type="text" class="form-control <?= ($enviado && !empty($mensajeErrorNombre)) ? 'is-invalid' : '' ?>" name="nombreRepuesto" value="<?= htmlspecialchars($nombre) ?>">
                    <?php if($enviado && !empty($mensajeErrorNombre)): ?>
                        <div class="invalid-feedback"><?= $mensajeErrorNombre ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control <?= ($enviado && !empty($mensajeErrorDescripcion)) ? 'is-invalid' : '' ?>" name="descripcionRepuesto" rows="3"><?= htmlspecialchars($descripcion) ?></textarea>
                    <?php if($enviado && !empty($mensajeErrorDescripcion)): ?>
                        <div class="invalid-feedback"><?= $mensajeErrorDescripcion ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Marca</label>
                        <select class="form-select <?= ($enviado && !empty($mensajeErrorMarca)) ? 'is-invalid' : '' ?>" name="marcaRepuesto">
                            <option value="">Seleccione marca...</option>
                            <?php foreach($marcas as $m): ?>
                                <option value="<?= $m ?>" <?= ($marca === $m) ? 'selected' : '' ?>><?= $m ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if($enviado && !empty($mensajeErrorMarca)): ?>
                            <div class="invalid-feedback"><?= $mensajeErrorMarca ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select <?= ($enviado && !empty($mensajeErrorCategoria)) ? 'is-invalid' : '' ?>" name="categoria">
                            <option value="0">No establecido</option>
                            <?php foreach($categorias as $idCat => $nombreCat): ?>
                                <option value="<?= $idCat ?>" <?= ($categoria == $idCat) ? 'selected' : '' ?>><?= $nombreCat ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if($enviado && !empty($mensajeErrorCategoria)): ?>
                            <div class="invalid-feedback"><?= $mensajeErrorCategoria ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Costo Unitario</label>
                        <input type="number" step="0.01" class="form-control <?= ($enviado && !empty($mensajeErrorCosto)) ? 'is-invalid' : '' ?>" name="costoUnitario" value="<?= htmlspecialchars($costoUnitario) ?>">
                        <?php if($enviado && !empty($mensajeErrorCosto)): ?>
                            <div class="invalid-feedback"><?= $mensajeErrorCosto ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Precio de Venta</label>
                        <input type="number" step="0.01" class="form-control <?= ($enviado && !empty($mensajeErrorPrecio)) ? 'is-invalid' : '' ?>" name="precioVenta" value="<?= htmlspecialchars($precioVenta) ?>">
                        <?php if($enviado && !empty($mensajeErrorPrecio)): ?>
                            <div class="invalid-feedback"><?= $mensajeErrorPrecio ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" class="form-control <?= ($enviado && !empty($mensajeErrorStock)) ? 'is-invalid' : '' ?>" name="stock" value="<?= htmlspecialchars($stock) ?>">
                        <?php if($enviado && !empty($mensajeErrorStock)): ?>
                            <div class="invalid-feedback"><?= $mensajeErrorStock ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Proveedor</label>
                    <select class="form-select <?= ($enviado && !empty($mensajeErrorProveedor)) ? 'is-invalid' : '' ?>" name="proveedor">
                        <option value="0">No establecido</option>
                        <?php foreach($proveedores as $idProv => $nombreProv): ?>
                            <option value="<?= $idProv ?>" <?= ($proveedor == $idProv) ? 'selected' : '' ?>><?= $nombreProv ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if($enviado && !empty($mensajeErrorProveedor)): ?>
                        <div class="invalid-feedback"><?= $mensajeErrorProveedor ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select <?= ($enviado && !empty($mensajeErrorEstado)) ? 'is-invalid' : '' ?>" name="estadoRepuesto">
                        <option value="Activo" <?= ($estado === 'Activo') ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= ($estado === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                    <?php if($enviado && !empty($mensajeErrorEstado)): ?>
                        <div class="invalid-feedback"><?= $mensajeErrorEstado ?></div>
                    <?php endif; ?>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-custom" name="submitModificarRepuesto">Guardar Cambios</button>
                    <a href="/sc502-2c2025-grupo2/view/Administracion/Repuestos/repuestos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>