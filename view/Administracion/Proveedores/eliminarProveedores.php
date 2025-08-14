<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../../componentes/comprobarInicio.php';
$mysqli = abrirConexion();

$id = $_GET['id'];

$mysqli->query("DELETE FROM PROVEEDORES WHERE id_proveedor = $id");

cerrarConexion($mysqli);

        echo '<script>
        alert("Proveedor eliminado con éxito.")
        window.location.href = "proveedores.php"
        </script>';
?>