<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../accessoDatos/accesoDatos.php';

if (!isset($_SESSION["nombreUsuario"])) {
    echo '<script> 
        alert("Debe iniciar sesión para acceder a esta página."); 
        window.location.href = "login.php"; 
    </script>';
    exit; 
}

$mysqli = abrirConexion();

$citas = $mysqli->query(
    "SELECT 
        id_cita,
        DATE_FORMAT(fecha, '%Y-%m-%d') AS fecha,
        TIME_FORMAT(hora, '%h:%i %p') AS hora,
        motivo,
        estado
    FROM CITAS
    ORDER BY fecha DESC, hora DESC"
);

if (!$citas) {
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <?php include '../componentes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Citas</h2>    
        <?php if ($citas->num_rows > 0): ?>
            <?php while ($fila = $citas->fetch_assoc()): ?>
                <?php
                    // Definir clase badge según estado
                    if ($fila['estado'] == 'Pendiente') {
                        $claseBadge = 'bg-warning text-dark';
                    } elseif ($fila['estado'] == 'Completada') {
                        $claseBadge = 'bg-success';
                    } elseif ($fila['estado'] == 'Cancelada') {
                        $claseBadge = 'bg-danger';
                    } else {
                        $claseBadge = 'bg-secondary';
                    }
                ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
                            <div><strong>Fecha:</strong> <?= htmlspecialchars($fila['fecha']) ?></div>
                            <div><strong>Hora:</strong> <?= htmlspecialchars($fila['hora']) ?></div>
                            <div><strong>Estado:</strong> <span class="badge <?= $claseBadge ?>"><?= htmlspecialchars($fila['estado']) ?></span></div>
                            <div><strong>Motivo:</strong> <?= htmlspecialchars($fila['motivo']) ?></div>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No hay citas disponibles.</p>
        <?php endif; ?>

        <div class="d-flex justify-content-center my-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCita">
                <i class="fas fa-plus"></i> Agregar Cita
            </button>
        </div>

    </div>

    <?php include 'agregarCita.php'; ?>

</body>
</html>