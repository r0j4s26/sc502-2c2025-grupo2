<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    

    <?php include '../../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Categorias</h2>
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID Categoría</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Pintura</td>
                    <td>Productos relacionados a pintura automotriz</td>
                    <td><span class="badge bg-success">Activo</span></td>
                    <td>
                        <a href="modificarCategoria.php" class="btn btn-primary btn-sm">Modificar</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
                <i class="fas fa-plus"></i>Agregar Categoría
            </button>
        </div>

        <?php include 'agregarCategoria.php';?>
    </div>


</body>
</html>