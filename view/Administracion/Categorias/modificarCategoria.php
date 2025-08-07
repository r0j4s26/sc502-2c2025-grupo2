<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
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
                Modificar Categoría
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="idCategoria" class="form-label">ID Categoría</label>
                        <input type="text" class="form-control" id="idCategoria" value="1" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreCategoria" placeholder="Ej. Cajuela">
                    </div>

                    <div class="mb-3">
                        <label for="descripcionCategoria" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionCategoria" rows="3" placeholder="Descripción de la categoría..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estadoCategoria" class="form-label">Estado</label>
                        <select class="form-select" id="estadoCategoria">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
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