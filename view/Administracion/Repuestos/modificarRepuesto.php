<!DOCTYPE html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli2 = abrirConexion();

$id = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
$mensajeExito = '';

$repuestoID = $mysqli2->query("
    SELECT 
        P.nombre AS nom,
        P.descripcion AS descri,
        P.marca AS marca,
        P.costo_unitario AS costoU,
        P.precio_venta AS precioVenta,
        P.estado AS estado,
        IFNULL(P.id_proveedores, 0) AS idproveedor,
        IFNULL(P.id_categoria, 0) AS idCategoria,
        IFNULL(PR.nombre, '0') AS proveedor,
        IFNULL(C.nombre, '0') AS categoria
    FROM PRODUCTOS P
    LEFT JOIN PROVEEDORES PR ON P.id_proveedores = PR.id_proveedores
    LEFT JOIN CATEGORIAS C ON P.id_categoria = C.id_categoria
    WHERE P.id_producto = $id
")->fetch_assoc();


$proveedores = [];
$resultado1 = $mysqli2->query("SELECT id_proveedores, nombre FROM PROVEEDORES");
while ($row = $resultado1->fetch_assoc()) {
    $proveedores[] = $row;
}


$categorias = [];
$resultado2 = $mysqli2->query("SELECT id_categoria, nombre FROM CATEGORIAS");
while ($row = $resultado2->fetch_assoc()) {
    $categorias[] = $row;
}

cerrarConexion($mysqli2);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $mysqli = abrirConexion();
        $estadoCategoria = $_POST['estadoRepuesto'];
        $idEstado = ($estadoCategoria == "Activo") ? 1 : 0;

        $stmt = $mysqli->prepare("
            UPDATE PRODUCTOS 
            SET nombre = ?, descripcion = ?, marca = ?, costo_unitario = ?, precio_venta = ?, estado = ?, id_proveedores = ?, id_categoria = ? 
            WHERE id_producto = ?
        ");
        $stmt->bind_param(
            "sssdidiii", 
            $_POST['nombreRepuesto'], 
            $_POST['descripcionRepuesto'], 
            $_POST['marcaRepuesto'], 
            $_POST['costoUnitario'], 
            $_POST['precioVenta'], 
            $idEstado, 
            $_POST['proveedor'], 
            $_POST['categoria'], 
            $id
        );

        if ($stmt->execute()) {
            $repuestoID = $mysqli2->query("
                SELECT 
                P.nombre AS nom,
                P.descripcion AS descri,
                P.marca AS marca,
                P.costo_unitario AS costoU,
                P.precio_venta AS precioVenta,
                P.estado AS estado,
                IFNULL(P.id_proveedores, 0) AS idproveedor,
                IFNULL(P.id_categoria, 0) AS idCategoria,
                IFNULL(PR.nombre, '0') AS proveedor,
                IFNULL(C.nombre, '0') AS categoria
                FROM PRODUCTOS P
                LEFT JOIN PROVEEDORES PR ON P.id_proveedores = PR.id_proveedores
                LEFT JOIN CATEGORIAS C ON P.id_categoria = C.id_categoria
                WHERE P.id_producto = $id
            ")->fetch_assoc();
            cerrarConexion($mysqli);
            $mensajeExito = "¡Repuesto actualizado correctamente! Redirigiendo...";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '/sc502-2c2025-grupo2/view/Administracion/Repuestos/repuestos.php';
                }, 2500);
            </script>";
        } else {
            throw new Exception("Sucedió un error al realizar la actualización.");
        }
    } catch (Exception $e) {
         echo "Error: " . $e->getMessage();
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
                Modificar Repuesto
            </div>
            <div class="card-body">
                <?php if($mensajeExito): ?>
                    <div class="alert alert-success"><?= $mensajeExito ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del repuesto</label>
                        <input type="text" class="form-control" name="nombreRepuesto" id="nombre" value="<?= $repuestoID["nom"] ?>">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcionRepuesto" id="descripcion" rows="3"><?= $repuestoID["descri"] ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" name="marcaRepuesto" id="marca" value="<?= $repuestoID["marca"] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria">
                                <option value="0" <?= ($repuestoID["idCategoria"] == 0) ? 'selected' : '' ?>>No establecido</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>" 
                                        <?= ($repuestoID["idCategoria"] == $cat['id_categoria']) ? 'selected' : '' ?>>
                                        <?= $cat['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="costo" class="form-label">Costo Unitario</label>
                            <input type="number" step="0.01" class="form-control" name="costoUnitario" id="costo" value="<?= $repuestoID["costoU"] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio de Venta</label>
                            <input type="number" step="0.01" class="form-control" name="precioVenta" id="precio" value="<?= $repuestoID["precioVenta"] ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <select class="form-select" id="proveedor" name="proveedor">
                            <option value="0" <?= ($repuestoID["idproveedor"] == 0) ? 'selected' : '' ?>>No establecido</option>
                            <?php foreach ($proveedores as $prov): ?>
                                <option value="<?= $prov['id_proveedores'] ?>" 
                                    <?= ($repuestoID["idproveedor"] == $prov['id_proveedores']) ? 'selected' : '' ?>>
                                    <?= $prov['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estadoRepuesto">
                            <option value="Activo" <?= ($repuestoID["estado"] == 1) ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= ($repuestoID["estado"] == 0) ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                        <a href="/sc502-2c2025-grupo2/view/Administracion/Repuestos/repuestos.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>