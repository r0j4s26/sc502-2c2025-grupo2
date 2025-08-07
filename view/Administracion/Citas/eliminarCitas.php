<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

$id = $_GET['id'];

$mysqli->query("DELETE FROM CITAS WHERE id_cita");

cerrarConexion($mysqli);

        echo '<script>
        alert("Cita eliminada correctamente.")
        window.location.href = "citas.php"
        </script>';

?>