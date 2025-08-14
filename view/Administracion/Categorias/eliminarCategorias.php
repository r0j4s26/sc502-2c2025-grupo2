<?php
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM CATEGORIAS WHERE id_categoria = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    echo '<script>
        alert("Categoria eliminada correctamente.");
        window.location.href = "/sc502-2c2025-grupo2/view/Administracion/Categorias/categorias.php";
    </script>';
} else {
    echo 'ID de tarea inválido.';
}
?>
