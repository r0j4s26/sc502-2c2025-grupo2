<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

$resultado = $mysqli->query("SELECT * FROM PROVEEDORES ORDER BY id_proveedor");

if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores | MotoRepuestos Rojas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

    <?php include '../../componentes/navbar.php' ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Proveedores</h2>
        <div class="d-flex mb-3">
            <a href="agregarProveedores.php" class="btn btn-danger">Agregar Proveedor</a>
        </div>

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-danger text-center">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Metodo de Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody class="text-center">
                <?php while ($u = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $u['id_proveedor'] ?> </td>
                        <td><?php echo $u['nombre'] ?> </td>
                        <td><?php echo $u['telefono'] ?> </td>
                        <td><?php echo $u['correo'] ?> </td>
                        <td><?php echo $u['direccion'] ?> </td>
                        <td><?php echo $u['metodo_pago'] ?> </td>
                        <td><?php echo $u['estado'] ?> </td>
                        <td>
                            <a href="modificarProveedores.php?id=<?php echo $u['id_proveedor'] ?>" class="btn btn-outline-primary">Modificar</a>
                            <a href="eliminarProveedores.php?id=<?php echo $u['id_proveedor'] ?>" onclick="return confirm('Desea eliminar el proveedor?')" class="btn btn-outline-danger">Eliminar</a>
                        </td>
                    </tr>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Inicializar DataTables -->
    <script>
        $(document).ready(function () {
            $('table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
    
</body>
</html>