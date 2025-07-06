<?php

// prueba
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

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Registrar cuenta nueva</h4>
                    </div>
                    <div class="card-body">
                        <form id="registroForm" method="post" action="">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <div class="input-row-inline">
                                    <input type="text" class="form-control mb-2" maxlenght="50" id="txtNombre"
                                        name="txtNombre" placeholder="Ingrese su nombre" required />
                                    <input type="text" class="form-control mb-3" maxlenght="50" id="txtApellidos"
                                        name="txtApellidos" placeholder="Ingrese su apellido" required />
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electronico</label>
                                    <input type="email" class="form-control" id="txtEmail" name="txtEmail"
                                        placeholder="ejemplo@correo.com" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="txtContrasenna" name="txtContrasenna" placeholder="Ingrese su Contraseña" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="txtConfContrasenna" name="txtConfContrasenna" placeholder="Confirme su Contraseña" required>
                                </div>

                                <div class="form-check mb-3">
                                    <label for="condiciones" class="form-check-label">Acepto terminos y condiciones</label>
                                    <input type="checkbox" class="form-check-input" id="checkCondiciones" name="checkCondiciones" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Registrarse</button>  
                                
                                <p class="text-center mt-3 "> Ya tienes una cuenta? <a href="login.php">Iniciar Sesion</a> </p>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>