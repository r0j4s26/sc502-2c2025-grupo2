<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../accessoDatos/accesoDatos.php';

$mensaje = '';
$tipoAlerta = '';
$email = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['txtEmail'] ?? '');
    $password = trim($_POST['txtPassword'] ?? '');

    if ($email && $password) {
        $mysqli = abrirConexion();

        if (!$mysqli) {
            $mensaje = 'Error al conectar a la base de datos.';
            $tipoAlerta = 'error';
        } else {
            $sql = "SELECT id_cliente, contrasena, nombre, apellidos FROM CLIENTES WHERE email = ? LIMIT 1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($idCliente, $hash, $nombre, $apellidos);
                $stmt->fetch();

                if (password_verify($password, $hash)) {
                    $_SESSION['idCliente'] = $idCliente;
                    $_SESSION['nombreCompleto'] = $nombre . ' ' . $apellidos;
                    header('Location: mototienda.php');
                    exit;
                } else {
                    $mensaje = 'Contraseña incorrecta.';
                    $tipoAlerta = 'error';
                }
            } else {
                $mensaje = 'Correo no registrado.';
                $tipoAlerta = 'error';
            }

            $stmt->close();
            cerrarConexion($mysqli);
        }
    } else {
        $mensaje = 'Debe ingresar correo y contraseña.';
        $tipoAlerta = 'warning';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inicio Sesión | MotoRepuestos Rojas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body style="background: linear-gradient(135deg, #8B0000 0%, #800000 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg rounded-4 border-0">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <i class="fa-solid fa-motorcycle fa-3x text-danger mb-3"></i>
              <h3 class="card-title fw-bold">Iniciar Sesión</h3>
            </div>


            <form id="loginForm" action="login.php" method="post" novalidate>
 
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Correo Electrónico" value="<?= htmlspecialchars($email) ?>" required >

                <label for="txtEmail"><i class="fas fa-envelope me-2"></i>Correo Electrónico</label>
              </div>

              <div class="form-floating mb-4">
                <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Contraseña" >
                <label for="txtPassword"><i class="fas fa-lock me-2"></i>Contraseña</label>
              </div>

              <button class="btn btn-danger w-100 py-2" type="submit">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
              </button>
            </form>

            <p class="text-center mt-4 mb-0">
              ¿No tiene una cuenta?
              <a href="registro.php" class="fw-bold text-danger text-decoration-none">Regístrese aquí</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php if ($mensaje): ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
      icon: '<?= $tipoAlerta ?>',
      title: '<?= $mensaje ?>',
      confirmButtonColor: '#8B0000'
    });
  });
</script>
<?php endif; ?>
</body>
</html>


