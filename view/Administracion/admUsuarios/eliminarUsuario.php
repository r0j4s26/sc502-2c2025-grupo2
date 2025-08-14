<?php
require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM USUARIOS WHERE id_cliente= ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    echo '<script>
        alert("Categoria eliminada correctamente.");
        window.location.href = "/sc502-2c2025-grupo2/view/Administracion/admUsuarios/usuarios.php";
    </script>';
} else {
    echo 'ID de tarea inválido.';
}
?>