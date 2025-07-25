<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../../accessoDatos/accesoDatos.php';

function mostrarAlerta($tipo, $mensaje, $redireccion = null) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$tipo',
                title: '$mensaje',
                confirmButtonColor: '#d33'
            }).then(() => {
                " . ($redireccion ? "window.location.href = '$redireccion';" : "window.history.back();") . "
            });
        });
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['txtNombre'] ?? '');
    $apellidos = trim($_POST['txtApellidos'] ?? '');
    $telefono = trim($_POST['txtTelefono'] ?? '');
    $email = trim($_POST['txtEmail'] ?? '');
    $contrasenna = trim($_POST['txtContrasenna'] ?? '');
    $confContrasenna = trim($_POST['txtConfContrasenna'] ?? '');
    $aceptaCondiciones = isset($_POST['checkCondiciones']);

    if (!$nombre || !$apellidos || !$telefono || !$email || !$contrasenna || !$confContrasenna || !$aceptaCondiciones) {
        mostrarAlerta('warning', 'Complete todos los campos y acepte los términos.');
    }

    if ($contrasenna !== $confContrasenna) {
        mostrarAlerta('warning', 'Las contraseñas no coinciden.');
    }

    $contrasennaHash = password_hash($contrasenna, PASSWORD_DEFAULT);

    try {
        $mysqli = abrirConexion();

        if (!$mysqli) {
            mostrarAlerta('error', 'No se pudo conectar a la base de datos.');
        }

        // Verificar si el correo ya existe
        $stmtCheck = $mysqli->prepare("SELECT id_cliente FROM CLIENTES WHERE email = ?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $stmtCheck->close();
            mostrarAlerta('info', 'Este correo ya está registrado.');
        }
        $stmtCheck->close();

        // Insertar cliente
        $stmt = $mysqli->prepare("INSERT INTO CLIENTES (nombre, apellidos, telefono, email, contrasena) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $nombre, $apellidos, $telefono, $email, $contrasennaHash);

        if ($stmt->execute()) {
            $stmt->close();
            cerrarConexion($mysqli);
            mostrarAlerta('success', 'Cuenta creada con éxito. Inicie sesión.', 'login.php');
        } else {
            mostrarAlerta('error', 'Error al registrar: ' . $stmt->error);
        }

    } catch (Exception $e) {
        mostrarAlerta('error', 'Error de conexión: ' . $e->getMessage());
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <title>Registro MotoRepuestos Rojas</title>
</head>

<body style="background: linear-gradient(135deg, #8B0000 0%, #800000 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow rounded-4 border-0">

          <div class="card-header bg-white text-center border-0 pb-0">
            <h4 class="fw-bold mb-0">Créate una cuenta</h4>
          </div>
          <div class="card-body p-5">
            <form id="registroForm" method="post" action="">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="txtNombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" maxlength="50" id="txtNombre" name="txtNombre" placeholder="Nombre" required />
                </div>
                <div class="col-md-6">
                  <label for="txtApellidos" class="form-label">Apellidos</label>
                  <input type="text" class="form-control" maxlength="50" id="txtApellidos" name="txtApellidos" placeholder="Apellidos" required />
                </div>
              </div>

              <div class="mb-3 mt-3">
                <label for="txtTelefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" maxlength="20" id="txtTelefono" name="txtTelefono" placeholder="Número de teléfono" required>
              </div>

              <div class="mb-3">
                <label for="txtEmail" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="ejemplo@correo.com" required>
              </div>

              <div class="mb-3">
                <label for="txtContrasenna" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="txtContrasenna" name="txtContrasenna" placeholder="Ingrese una contraseña" required>
              </div>

              <div class="mb-3">
                <label for="txtConfContrasenna" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="txtConfContrasenna" name="txtConfContrasenna" placeholder="Confirme su contraseña" required>
              </div>

              <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="checkCondiciones" name="checkCondiciones" required>
                <label for="checkCondiciones" class="form-check-label">
                  Acepto <a href="#" class="text-decoration-none fw-semibold text-danger link-hover">términos y condiciones</a>.
                </label>
              </div>

              <button type="submit" class="btn btn-danger w-100 py-2">Registrarse</button>

              <p class="text-center mt-4 mb-0">
                ¿Ya tienes una cuenta?
                <a href="login.php" class="fw-bold text-danger text-decoration-none link-hover">Inicia sesión aquí</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>


</html>