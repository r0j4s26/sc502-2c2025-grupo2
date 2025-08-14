<?php
session_start();
require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';

if (!isset($_GET['id_cliente'])) {
    header("Location: usuarios.php");
    exit();
}

$idCliente = $_GET['id_cliente'];
$mysqli = abrirConexion();
$stmt = $mysqli->prepare("DELETE FROM usuarios WHERE id_cliente = ?");
$stmt->bind_param("i", $idCliente);

if($idCliente == $_SESSION['idUsuario']){
    $stmt->close();
    cerrarConexion($mysqli);
    header("Location: usuarios.php?error=1");
    exit();
}elseif ($stmt->execute()) {
    $stmt->close();
    cerrarConexion($mysqli);
    header("Location: usuarios.php?eliminado=1");
    exit();
} else {
    $error = $stmt->error;
    $stmt->close();
    cerrarConexion($mysqli);
    die("Error al eliminar el usuario: $error");
}
?>