<?php
function abrirConexion(){

try{

    $host = "127.0.0.1";
    $user = "root";
    $password = "1234";
    $db = "MOTOREPUESTOSROJAS";

    $mysqli = new mysqli($host, $user, $password, $db);

    if($mysqli->connect_error){
        throw new exception("Sucedió un error al realizar la conexión a la base de datos.");
    }

    $mysqli->set_charset('utf8mb4');

    return $mysqli;

}catch (Exception $e){

    return false;
}

}

function cerrarConexion($mysqli){
    if($mysqli instanceof mysqli){
        $mysqli->close();
    }
}
?>
