<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../accessoDatos/accesoDatos.php';
$mysqli = abrirConexion();

 try{
     if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $id_cliente = $_SESSION['idCliente']; 

         $stmt = $mysqli->prepare("INSERT INTO CITAS (fecha, hora, motivo, estado, id_cliente)
         VALUES(?, ?, ?, ?, ?)");
         $stmt->bind_param("ssssi", $_POST["fecha"], $_POST["hora"], $_POST["motivo"], $_POST["estado"], $id_cliente);

         if ($stmt->execute()) {

             cerrarConexion($mysqli);

             echo '<script>
             alert("La cita se agendó correctamente.")
             window.location.href = "citas.php"
             </script>';
         } else {
             throw new exception("Sucedio un error al agendar la cita.");
         }
     }
 } catch (Exception $e) {
     echo '<script> alert("Sucedio un error al agendar la cita.") </script>';
     echo '<script> console.log(" ' . $e->getMessage() . ' ") </script>';
 }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Cita | MotoRepuestos Rojas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card-sombra {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .card-header-custom {
            background-color: #8B0000;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .btn-custom {
            background-color: #8B0000;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #a52a2a;
        }
    </style>
</head>

<body class="bg-light">

    <?php include '../../componentes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="card card-sombra">
            <div class="card-header card-header-custom">Agregar Cita</div>
            <div class="card-body">

                <form id="agregaCitaFrom" method="post" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fechaCita" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="horaCita" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estadoCita" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" aria-label="Default select example" required>
                            <option select>--Seleccione un estado--</option>
                            <option value="Confirmada">Confirmada</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="motivoCita" class="form-label">Motivo</label>
                        <textarea class="form-control" id="motivo" name="motivo" rows="3" placeholder="Describa el motivo de la cita..." required></textarea>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-custom" type="submit"><strong>Guardar</strong></button>
                        <a class="btn btn-secondary" href="citas.php"><strong>Cancelar</strong></a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>

</html>