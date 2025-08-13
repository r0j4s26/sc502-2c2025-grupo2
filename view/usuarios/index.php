<?php
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_SESSION["nombreUsuario"] = $_POST["txtEmail"];
  header("Location: mototienda.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../scripts/login.js"></script>
    <title>Inicio Sesión | MotoRepuestos Rojas</title>

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

            <form id="loginForm" method="post">
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Correo Electrónico" >
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

            <div id="loginError" class="alert alert-danger text-center mt-3" style="display: none;">
              Credenciales Inválidas
            </div>

            <p class="text-center mt-4 mb-0">
              ¿No tiene una cuenta?
              <a href="registro.php" class="fw-bold text-danger text-decoration-none">Regístrese aquí</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>