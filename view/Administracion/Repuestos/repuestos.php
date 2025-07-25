<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Repuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <?php include '../../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Repuestos</h2>

        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Costo Unitario</th>
                    <th>Precio Venta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Filtro de aceite</td>
                    <td>Filtro para motor de 4 cilindros</td>
                    <td>ACDelco</td>
                    <td>Motor</td>
                    <td>₡2,500</td>
                    <td>₡4,000</td>
                    <td>Activo</td>
                    <td class="text-center">
                        <a href="modificarRepuesto.php" class="btn btn-warning btn-sm me-2">Modificar</a>
                        <a href="" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                <tr>
                    <td>Batería 12V</td>
                    <td>Batería para vehículo liviano</td>
                    <td>Yuasa</td>
                    <td>Eléctrico</td>
                    <td>₡20,000</td>
                    <td>₡35,000</td>
                    <td>Activo</td>
                    <td class="text-center">
                        <a href="modificarRepuesto.php" class="btn btn-warning btn-sm me-2">Modificar</a>
                        <a href="" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mb-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoRepuesto">

                <i class="fas fa-plus"></i>Agregar nuevo repuesto

            </button>
        </div>

        <?php include 'agregarRepuesto.php'; ?>

    </div>
</body>
</html>