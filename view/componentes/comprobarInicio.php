<?php
if (!isset($_SESSION["nombreUsuario"])) {
    echo '<script>
        alert("Debe iniciar sesión para acceder a esta página.");
        window.location.href = "/sc502-2c2025-grupo2/view/usuarios/index.php";
    </script>';
    exit();
}
?>