<?php
session_start();
require_once '../../accessoDatos/accesoDatos.php';

$checkout = $_SESSION['checkout'] ?? null;
if (!$checkout || empty($checkout['items'])) {
  header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=vacio'); exit;
}

$total  = (float)$checkout['total'];
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old']    ?? [];
unset($_SESSION['errors'], $_SESSION['old']); 

$ef = !empty($old['mp_efectivo']);    
$m  = $old['metodo_pago'] ?? '';       
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Método de pago</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5/bootstrap-4.min.css" rel="stylesheet">
  <style>.card-soft{border:0;border-radius:1rem;box-shadow:0 10px 25px rgba(0,0,0,.08);}</style>
</head>
<body class="bg-light">
  <?php include '../componentes/navbar.php'; ?>

  <div class="container my-4">
    <div class="row g-4">
      <!-- Resumen -->
      <div class="col-12 col-lg-7">
        <div class="card card-soft">
          <div class="card-header bg-white fw-semibold">Resumen de compra</div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Producto</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr>
                </thead>
                <tbody>
                  <?php foreach ($checkout['items'] as $it): ?>
                    <tr>
                      <td><?= htmlspecialchars($it['nombre']) ?></td>
                      <td class="text-center"><?= (int)$it['cantidad'] ?></td>
                      <td class="text-end">₡<?= number_format((float)$it['precio'], 2, '.', ',') ?></td>
                      <td class="text-end">₡<?= number_format((float)$it['subtotal'], 2, '.', ',') ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr><th colspan="3" class="text-end">Total</th><th class="text-end">₡<?= number_format($total, 2, '.', ',') ?></th></tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>


      <div class="col-12 col-lg-5">
        <form method="post" action="/sc502-2c2025-grupo2/view/usuarios/procesar_pago.php" class="card card-soft">
          <div class="card-header bg-white fw-semibold">Elegí el método de pago</div>
          <div class="card-body">

   
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="mpEfectivoChk" name="mp_efectivo" value="1" <?= $ef ? 'checked' : '' ?>>
              <label class="form-check-label fw-semibold" for="mpEfectivoChk">
                Pagar en efectivo (contra entrega / mostrador)
              </label>
            </div>

            <div class="small text-muted mb-2">Si no elegís efectivo, seleccioná una de estas opciones:</div>


            <div class="form-check">
              <input class="form-check-input" type="radio" name="metodo_pago" id="mpTarjeta" value="Tarjeta" <?= $m==='Tarjeta'?'checked':''; ?>>
              <label class="form-check-label" for="mpTarjeta">Tarjeta (crédito/débito)</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="metodo_pago" id="mpSinpe" value="SINPE" <?= $m==='SINPE'?'checked':''; ?>>
              <label class="form-check-label" for="mpSinpe">SINPE Móvil / Transferencia</label>
            </div>

            <div class="border rounded p-3 mb-3">
              <div class="mb-2 fw-semibold">Datos de tarjeta (solo si elegís Tarjeta)</div>
              <div class="mb-2">
                <label for="tarjeta_numero" class="form-label">Número de tarjeta</label>
                <input type="text" class="form-control" id="tarjeta_numero" name="tarjeta_numero"
                       value="<?= htmlspecialchars($old['tarjeta_numero'] ?? '') ?>" placeholder="Solo dígitos, sin espacios">
              </div>
              <div class="row g-2">
                <div class="col-4">
                  <label for="tarjeta_mes" class="form-label">Mes (MM)</label>
                  <input type="text" class="form-control" id="tarjeta_mes" name="tarjeta_mes"
                         value="<?= htmlspecialchars($old['tarjeta_mes'] ?? '') ?>">
                </div>
                <div class="col-4">
                  <label for="tarjeta_anio" class="form-label">Año (YY)</label>
                  <input type="text" class="form-control" id="tarjeta_anio" name="tarjeta_anio"
                         value="<?= htmlspecialchars($old['tarjeta_anio'] ?? '') ?>">
                </div>
                <div class="col-4">
                  <label for="tarjeta_cvv" class="form-label">CVV</label>
                  <input type="password" class="form-control" id="tarjeta_cvv" name="tarjeta_cvv"
                         value="<?= htmlspecialchars($old['tarjeta_cvv'] ?? '') ?>">
                </div>
              </div>
              <div class="mt-2">
                <label for="tarjeta_nombre" class="form-label">Nombre en la tarjeta</label>
                <input type="text" class="form-control" id="tarjeta_nombre" name="tarjeta_nombre"
                       value="<?= htmlspecialchars($old['tarjeta_nombre'] ?? '') ?>">
              </div>
            </div>


            <div class="border rounded p-3 mb-3">
              <div class="mb-2 fw-semibold">Datos de SINPE (solo si elegís SINPE)</div>
              <label for="sinpe_ref" class="form-label">Referencia/ID (opcional)</label>
              <input type="text" class="form-control" id="sinpe_ref" name="sinpe_ref"
                     value="<?= htmlspecialchars($old['sinpe_ref'] ?? '') ?>" placeholder="Ref. del banco o SINPE Móvil">
            </div>

            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección de entrega (opcional)</label>
              <textarea class="form-control" id="direccion" name="direccion" rows="3"><?= htmlspecialchars($old['direccion'] ?? '') ?></textarea>
            </div>

            <div class="d-grid gap-2">
              <button class="btn btn-success" type="submit">Confirmar y pagar</button>
              <a href="/sc502-2c2025-grupo2/view/usuarios/carrito.php" class="btn btn-outline-secondary">← Volver al carrito</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    <?php if (!empty($errors)):
      $msg = implode('<br>', array_map('htmlspecialchars', $errors)); ?>
      Swal.fire({icon:'error', title:'Revisá la información', html:'<?= $msg ?>'});
    <?php endif; ?>
  </script>
</body>
</html>
