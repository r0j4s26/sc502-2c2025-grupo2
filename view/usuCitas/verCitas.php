<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../componentes/comprobarInicio.php';

if (!isset($_SESSION["nombreUsuario"])) {
    echo '<script>
        window.location.href = "login.php";
    </script>';
    exit;
}

$mysqli = abrirConexion();
$idUsuario = $_SESSION['idUsuario'];

$citas = $mysqli->query(
    "SELECT 
        id_cita,
        DATE_FORMAT(fecha, '%Y-%m-%d') AS fecha,
        TIME_FORMAT(hora, '%h:%i %p') AS hora,
        motivo,
        estado
    FROM CITAS
    WHERE id_cliente = $idUsuario
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body class="bg-light">
    <?php include '../componentes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lista de Citas</h2>

        <?php if ($citas->num_rows > 0): ?>
            <?php while ($fila = $citas->fetch_assoc()): ?>
                <?php
                    $claseBadge = match($fila['estado']) {
                        'Pendiente' => 'bg-warning text-dark',
                        'Completada' => 'bg-success',
                        'Cancelada' => 'bg-danger',
                        default => 'bg-secondary',
                    };
                ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">
                            <div><strong>Fecha:</strong> <?= $fila['fecha'] ?></div>
                            <div><strong>Hora:</strong> <?= $fila['hora'] ?></div>
                            <div><strong>Estado:</strong> <span class="badge <?= $claseBadge ?>"><?= $fila['estado'] ?></span></div>
                            <div><strong>Motivo:</strong> <?= $fila['motivo'] ?></div>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <button class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $fila['id_cita'] ?>">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No hay citas disponibles.</p>
        <?php endif; ?>

        <div class="d-flex justify-content-center my-4">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAgregarCita">
                <i class="fas fa-plus"></i> Agendar Cita
            </button>
        </div>
    </div>

    <?php include 'usuAgregarCitas.php'; ?>

    <script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-eliminar');
        if (!btn) return;
        e.preventDefault();
        const idCita = btn.getAttribute('data-id');

        Swal.fire({
            title: '¿Esta seguro que desea eliminar esta cita?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'usuEliminarCitas.php?id=' + encodeURIComponent(idCita);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(location.search);

        if (params.get('agregado') === '1') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Cita agregada exitosamente',
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
            params.delete('agregado');
        }

        if (params.get('eliminado') === '1') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Cita eliminada exitosamente',
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
            params.delete('eliminado');
        }

        const url = window.location.origin + window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        history.replaceState({}, '', url);
    });
    </script>
</body>
</html>