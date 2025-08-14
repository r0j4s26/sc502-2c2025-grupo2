<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$id = intval($_GET['id'] ?? 0);
if ($id>0) {
    $mysqli->query("DELETE FROM PROVEEDORES WHERE id_proveedor = $id");
}

cerrarConexion($mysqli);
header("Location: proveedores.php?eliminado=1");
exit();
?>