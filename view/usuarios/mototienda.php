<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoRepuestos Rojas| Repuestos y Accesorios </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/sc502-2c2025-grupo2/scripts/catalogo.js"></script>
</head>
<body>
    <?php include '../componentes/navbar.php'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Catálogo de Productos</h2>
        <div class="row" id="catalogo-list"></div>
    </div>
    <div class="p-5 text-center bg-image" style="
    background-image: url('/sc502-2c2025-grupo2/img/inicio.png');
    height: 25rem;
    background-size: cover;
    background-position: center;
  ">
        <div class="mask" style="background-color: rgba(0, 0, 0, 0.6); height: 100%;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white">
            <h1 class="mb-3 fw-bold display-4">Catalogo de productos</h1>
            <h5 class="mb-4">Mejor calidad y precio en un solo lugar.</h5>
            </div>
        </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row" id="catalogo-list"></div>
    </div>

</body>
</html>