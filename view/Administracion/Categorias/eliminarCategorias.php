<?php
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

$mysqli = abrirConexion();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM CATEGORIAS WHERE id_categoria = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: categorias.php?eliminado=1");
        exit();
    } else {
        echo 'Error al eliminar la categoría: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo 'ID de categoría inválido.';
}

cerrarConexion($mysqli);
?>