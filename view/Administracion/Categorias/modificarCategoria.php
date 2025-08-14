<!DOCTYPE html>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../componentes/comprobarInicio.php';

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli2 = abrirConexion();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$mensajeExito = '';
$errorNombre = $errorDescripcion = $errorEstado = "";

$categoriaID = $mysqli2->query("SELECT * FROM CATEGORIAS WHERE id_categoria = $id")->fetch_assoc();

cerrarConexion($mysqli2);

if (!$categoriaID) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Categoría no encontrada",
            text: "La categoría solicitada no existe.",
            confirmButtonText: "Aceptar"
        }).then(function(){ window.location.href = "/sc502-2c2025-grupo2/view/Administracion/Categorias/categorias.php"; });
    </script>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombreCategoria']);
    $descripcion = trim($_POST['descripcionCategoria']);
    $estadoCategoria = $_POST['estadoCategoria'];
    $idEstado = ($estadoCategoria == "Activo") ? 1 : 0;

    $errores = 0;

    if (empty($nombre)) {
        $errorNombre = "El nombre es obligatorio";
        $errores++;
    }

    if (empty($descripcion)) {
        $errorDescripcion = "La descripción es obligatoria";
        $errores++;
    }

    if (!in_array($estadoCategoria, ["Activo", "Inactivo"])) {
        $errorEstado = "Seleccione un estado válido";
        $errores++;
    }

    if ($errores == 0) {
        try {
            $mysqli = abrirConexion();
            $stmt = $mysqli->prepare("UPDATE CATEGORIAS SET nombre = ?, descripcion = ?, estado = ? WHERE id_categoria = ?");
            $stmt->bind_param("ssii", $nombre, $descripcion, $idEstado, $id);

            if ($stmt->execute()) {
                $categoriaID = $mysqli->query("SELECT * FROM CATEGORIAS WHERE id_categoria = $id")->fetch_assoc();
                cerrarConexion($mysqli);
                header("Location: /sc502-2c2025-grupo2/view/Administracion/Categorias/categorias.php?modificado=1");
                exit();

                echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "¡Categoría actualizada correctamente!",
                        text: "La categoría se modificó con éxito.",
                        confirmButtonText: "Aceptar"
                    }).then(function(){ window.location.href = "/sc502-2c2025-grupo2/view/Administracion/Categorias/categorias.php"; });
                </script>';
                exit();
            } else {
                throw new Exception("Sucedió un error al realizar la actualización.");
            }
        } catch (Exception $e) {

            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            $msg = htmlspecialchars($e->getMessage(), ENT_QUOTES);
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: {$msg}',
                    confirmButtonText: 'Aceptar'
                });
            </script>";
            exit();
        }
    }
}
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                Modificar Categoría
            </div>
            <div class="card-body">

                <form method="POST">
                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre</label>
                        <input type="text" name="nombreCategoria" class="form-control <?= !empty($errorNombre) ? 'is-invalid' : '' ?>" id="nombreCategoria" 
                               value="<?= isset($_POST['nombreCategoria']) ? htmlspecialchars($_POST['nombreCategoria']) : htmlspecialchars($categoriaID['nombre']) ?>">
                        <?php if(!empty($errorNombre)): ?>
                            <div class="invalid-feedback"><?= $errorNombre ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="descripcionCategoria" class="form-label">Descripción</label>
                        <textarea class="form-control <?= !empty($errorDescripcion) ? 'is-invalid' : '' ?>" name="descripcionCategoria" id="descripcionCategoria" rows="3"><?= isset($_POST['descripcionCategoria']) ? htmlspecialchars($_POST['descripcionCategoria']) : htmlspecialchars($categoriaID['descripcion']) ?></textarea>
                        <?php if(!empty($errorDescripcion)): ?>
                            <div class="invalid-feedback"><?= $errorDescripcion ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="estadoCategoria" class="form-label">Estado </label>
                        <select class="form-select <?= !empty($errorEstado) ? 'is-invalid' : '' ?>" name="estadoCategoria" id="estadoCategoria">
                            <option value="Activo" <?= (isset($_POST['estadoCategoria']) ? $_POST['estadoCategoria'] == "Activo" : $categoriaID['estado']==1) ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= (isset($_POST['estadoCategoria']) ? $_POST['estadoCategoria'] == "Inactivo" : $categoriaID['estado']==0) ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                        <?php if(!empty($errorEstado)): ?>
                            <div class="invalid-feedback"><?= $errorEstado ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                        <a href="/sc502-2c2025-grupo2/view/Administracion/Categorias/categorias.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>