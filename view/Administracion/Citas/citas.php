<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';


$mysqli = abrirConexion();

$sql = "SELECT c.*, CONCAT(cl.nombre, ' ', cl.apellidos) AS nombre_completo 
        FROM CITAS c
        INNER JOIN USUARIOS cl ON c.id_cliente = cl.id_cliente
        ORDER BY c.fecha";

$resultado = $mysqli->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $mysqli->error);
}

cerrarConexion($mysqli);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas | MotoRepuestos Rojas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

  <?php include '../../componentes/navbar.php'; ?>

  <div class="container mt-5">
    <h2 class="mb-4 text-center">Lista de Citas</h2>
    <div class="d-flex justify-content-center mb-4">
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalAgregarCita">
            <i class="fas fa-plus"></i> Agendar cita
        </button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-danger text-center">
          <tr>
            <th>Id Cita</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Motivo</th>
            <th>Nombre Cliente</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <?php while ($u = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= $u['id_cita'] ?></td>
              <td><?= $u['fecha'] ?></td>
              <td><?= $u['hora'] ?></td>
              <td><?= $u['estado'] ?></td>
              <td><?= $u['motivo'] ?></td>
              <td><?= $u['nombre_completo'] ?></td>
              <td>
                <button type="button" class="btn btn-sm btn-danger me-1 btn-eliminar" data-id="<?= $u['id_cita'] ?>">Eliminar</button>
                <a href="modificarCitas.php?id=<?= $u['id_cita'] ?>" class="btn btn-sm btn-primary me-2">Modificar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php include 'agregarCitas.php'; ?>
  </div>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function () {
        $('table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });

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
                window.location.href = 'eliminarCitas.php?id=' + encodeURIComponent(idCita);
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

        if (params.get('modificado') === '1') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Cita modificada exitosamente',
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
            params.delete('modificado');
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