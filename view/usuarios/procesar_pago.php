<?php
session_start();
require_once '../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../componentes/comprobarInicio.php';

if (empty($_SESSION['checkout']['items'])) {
  header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=vacio'); exit;
}

$metodo = null;
if (!empty($_POST['mp_efectivo'])) {
  $metodo = 'Efectivo';
} elseif (!empty($_POST['metodo_pago'])) {
  $metodo = $_POST['metodo_pago'];
}

$direccion      = trim($_POST['direccion'] ?? '');
$tarjeta_numero = preg_replace('/\D+/', '', $_POST['tarjeta_numero'] ?? '');
$tarjeta_mes    = trim($_POST['tarjeta_mes'] ?? '');
$tarjeta_anio   = trim($_POST['tarjeta_anio'] ?? '');
$tarjeta_cvv    = trim($_POST['tarjeta_cvv'] ?? '');
$tarjeta_nombre = trim($_POST['tarjeta_nombre'] ?? '');
$sinpe_ref      = trim($_POST['sinpe_ref'] ?? '');

$validos = ['Efectivo','Tarjeta','SINPE'];
$errors  = [];

if ($metodo === null) {
  $errors[] = 'Debés seleccionar un método de pago (Efectivo, Tarjeta o SINPE).';
} elseif (!in_array($metodo, $validos, true)) {
  $errors[] = 'Método de pago inválido.';
}
if (strlen($direccion) > 500) {
  $errors[] = 'La dirección no puede exceder 500 caracteres.';
}


if ($metodo === 'Tarjeta') {
  if (!preg_match('/^\d{15,19}$/', $tarjeta_numero)) {
    $errors[] = 'El número de tarjeta debe tener entre 15 y 19 dígitos.';
  }
  if (!preg_match('/^\d{3,4}$/', $tarjeta_cvv)) {
    $errors[] = 'El CVV debe tener 3 o 4 dígitos.';
  }
  if (mb_strlen($tarjeta_nombre) < 3) {
    $errors[] = 'El nombre en la tarjeta es demasiado corto.';
  }
}
 
if ($metodo === 'SINPE') {
  if ($sinpe_ref === '') {
    $errors[] = 'Debés ingresar la referencia de la transferencia SINPE.';
  } elseif (!preg_match('/^[A-Za-z0-9\-\_]{3,40}$/', $sinpe_ref)) {
    $errors[] = 'La referencia SINPE contiene caracteres inválidos o supera 40 caracteres.';
  }
}



if (!empty($errors)) {
  $_SESSION['errors'] = $errors;
  $_SESSION['old']    = [
    'mp_efectivo'    => !empty($_POST['mp_efectivo']) ? '1' : '',
    'metodo_pago'    => $_POST['metodo_pago'] ?? '',
    'direccion'      => $direccion,
    'tarjeta_numero' => $tarjeta_numero,
    'tarjeta_mes'    => $tarjeta_mes,
    'tarjeta_anio'   => $tarjeta_anio,
    'tarjeta_cvv'    => $tarjeta_cvv,
    'tarjeta_nombre' => $tarjeta_nombre,
    'sinpe_ref'      => $sinpe_ref,
  ];
  header('Location: /sc502-2c2025-grupo2/view/usuarios/pago.php'); exit;
}


$items    = $_SESSION['checkout']['items'];
$total    = (float)$_SESSION['checkout']['total'];
$idCliente = isset($_SESSION['idUsuario']) ? (int)$_SESSION['idUsuario'] : 1;


$conn = abrirConexion();
if (!$conn) {
  $_SESSION['errors'] = ['No se pudo conectar a la base de datos.'];
  $_SESSION['old'] = $_POST;
  header('Location: /sc502-2c2025-grupo2/view/usuarios/pago.php'); exit;
}

$conn->begin_transaction();
try {

    $sqlPedido = "INSERT INTO PEDIDOS (fecha_entrega, direccion, total, id_cliente)
                  VALUES (DATE_ADD(NOW(), INTERVAL 3 DAY), ?, ?, ?)";
    $stPedido = $conn->prepare($sqlPedido);
    $stPedido->bind_param('sdi', $direccion, $total, $idCliente);

    if (!empty($_SESSION['checkout']['items']) && $stPedido->execute()) {
        $idPedido = $conn->insert_id;
        $sqlUpProd = "UPDATE PRODUCTOS SET stock = stock - ? WHERE id_producto = ?;";
        $sqlInProd = "INSERT INTO DETALLE_PRODUCTOS_PEDIDOS (id_pedido, id_producto, precio, sub_total, cantidad) VALUES (?, ?, ?, ?, ?)";
        $stUpProd = $conn->prepare($sqlUpProd);
        $stInProd = $conn->prepare($sqlInProd);

        foreach ($_SESSION['checkout']['items'] as $it) {
            $idProd   = (int)$it['id_producto'];
            $cantidad = (int)$it['cantidad'];
            $subTotal = (float)$it['subtotal'];
            $precio   = (float)$it['precio'];

            try {
                $stInProd->bind_param('iiddi', $idPedido, $idProd, $precio, $subTotal, $cantidad);
                $stUpProd->bind_param('ii', $cantidad, $idProd);

                $stUpProd->execute();
                $stInProd->execute();
            } catch(Exception $e) {
                error_log("Error al actualizar stock: " . $e->getMessage());
                throw $e; 
            }
        }

        $stUpProd->close();
        $stInProd->close();
    }

    $stPedido->close();

    $estadoFactura = 1;
    $stFac = $conn->prepare("INSERT INTO FACTURAS (fecha_factura, total, metodo_pago, estado, id_pedido)
                             VALUES (NOW(), ?, ?, ?, ?)");
    $stFac->bind_param('dsii', $total, $metodo, $estadoFactura, $idPedido);
    $stFac->execute();
    $stFac->close();

    $conn->commit();
    cerrarConexion($conn);

    $_SESSION['carrito'] = [];
    $_SESSION['checkout'] = null;
    $_SESSION['last_pedido_id'] = $idPedido;

    header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=pedido&id='.$idPedido); 
    exit;

} catch (Throwable $e) {
    $conn->rollback();
    cerrarConexion($conn);
    // Guardar el mensaje real del error
    $_SESSION['errors'] = [
        'Ocurrió un error al procesar el pago. Intentalo de nuevo.',
        'Detalle técnico: ' . $e->getMessage()
    ];
    $_SESSION['old'] = $_POST;
    header('Location: /sc502-2c2025-grupo2/view/usuarios/pago.php'); 
    exit;
}
