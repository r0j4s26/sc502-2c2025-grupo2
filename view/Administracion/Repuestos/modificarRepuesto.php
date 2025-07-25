<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
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
<body class="bg-light ">
    <?php include '../../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="card card-sombra">
        <div class="card-header card-header-custom">

        </div>
        <div class="card-body">
            <form>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del repuesto</label>
                <input type="text" class="form-control" id="nombre" placeholder="Ej. Filtro de aceite">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="3" placeholder="Detalles del repuesto..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="marca" placeholder="Ej. Bosch">
                </div>
                <div class="col-md-6 mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control" id="categoria" placeholder="Ej. Motor">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                <label for="costo" class="form-label">Costo Unitario</label>
                <input type="number" class="form-control" id="costo" placeholder="₡">
                </div>
                <div class="col-md-6 mb-3">
                <label for="precio" class="form-label">Precio de Venta</label>
                <input type="number" class="form-control" id="precio" placeholder="₡">
                </div>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado">
                <option value="Disponible">Disponible</option>
                <option value="No disponible">No disponible</option>
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