<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../componentes/comprobarInicio.php';
require_once '../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM CITAS WHERE id_cita = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    cerrarConexion($mysqli);
    header("Location: verCitas.php?eliminado=1");
    exit();
} else {
    cerrarConexion($mysqli);
    echo '<script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "ID de cita inválido."
        }).then(() => {
            window.location.href = "citas.php";
        });
    </script>';
}
?>