<?php
//aca va el login php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <title>Inicio Sesión | MotoRepuestos Rojas</title>
</head>

<body>

    <div class="bg-light">

        <div class="container d-flex justify-content-center align-items-center min-vh-100">

            <div class="card p-4 shadow-lg w-100">
                <h3 class="card-title text-center mb-4"><i class=" fa-solid fa-motorcycle"></i>Iniciar Sesión<i class=" fa-solid fa-motorcycle"></i></h3>

                <form id="loginForm" action="" method="post">

                    <div class="input-group mb-3">
                        <span class="input-group-text"> <i class="fas fa-envelope"></i> </span>
                        <input class="form-control" type="email" name="txtEmail" id="txtEmail"
                            placeholder="Correo Electrónico" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"> <i class="fas fa-lock"></i> </span>
                        <input class="form-control" type="password" name="txtPassword" id="txtPassword" required
                            placeholder="Contraseña">
                    </div>

                    <button class="btn btn-primary" type="submit">Iniciar</button>

                </form>

                <div id="loginError" class="alertaError text-danger mt-3" style="display: none;">Credenciales Inválidas
                </div>

                <p class="text-center mt-3 "> No tiene una cuenta? <a href="registro.php">Regístrese acá</a> </p>

            </div>
        </div>       
    </div>
</body>

</html>