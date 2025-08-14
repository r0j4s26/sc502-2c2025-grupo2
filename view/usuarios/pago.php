<?php
session_start();
require_once '../../accessoDatos/accesoDatos.php';
require_once __DIR__ . '/../componentes/comprobarInicio.php';
$checkout = $_SESSION['checkout'] ?? null;
if (!$checkout || empty($checkout['items'])) {
  header('Location: /sc502-2c2025-grupo2/view/usuarios/carrito.php?ok=vacio'); exit;
}

$total  = (float)$checkout['total'];
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old']    ?? [];
unset($_SESSION['errors'], $_SESSION['old']); 
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
        
        <form method="post" action="/sc502-2c2025-grupo2/view/usuarios/procesar_pago.php" id="formPago" class="card card-soft">
          <div class="card-header bg-white fw-semibold">Elige el método de pago</div>
          <div class="card-body">
            <p class="text-muted mb-3">Se abrirá una ventana para completar los datos del método elegido.</p>

            <div class="d-grid gap-2">
              <button type="button" class="btn btn-outline-dark" id="btnEfectivo">Efectivo</button>
              <button type="button" class="btn btn-outline-primary" id="btnTarjeta">Tarjeta</button>
              <button type="button" class="btn btn-outline-success" id="btnSinpe">SINPE</button>
            </div>

            <hr class="my-4">

            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección de entrega</label>
              <textarea class="form-control" id="direccionVisible" rows="3" placeholder="Provincia, cantón, distrito, señas exactas..."><?= htmlspecialchars($old['direccion'] ?? '') ?></textarea>
            </div>


            <input type="hidden" name="metodo_pago" id="metodo_pago">

            <input type="hidden" name="direccion" id="direccion">

            <input type="hidden" name="tarjeta_numero" id="tarjeta_numero">
            <input type="hidden" name="tarjeta_mes" id="tarjeta_mes">
            <input type="hidden" name="tarjeta_anio" id="tarjeta_anio">
            <input type="hidden" name="tarjeta_cvv" id="tarjeta_cvv">
            <input type="hidden" name="tarjeta_nombre" id="tarjeta_nombre">

            <input type="hidden" name="sinpe_ref" id="sinpe_ref">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>

    function syncDireccion() {
      document.getElementById('direccion').value = document.getElementById('direccionVisible').value.trim();
    }

    // EFECTIVO
    document.getElementById('btnEfectivo').addEventListener('click', async () => {
      const ok = await Swal.fire({
        title: 'Pago en efectivo',
        html: 'Confirmá que realizarás el pago en efectivo al recibir o en mostrador.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      });
      if (!ok.isConfirmed) return;

      document.getElementById('metodo_pago').value = 'Efectivo';
      syncDireccion();
      document.getElementById('formPago').submit();
    });

  // TARJETA
document.getElementById('btnTarjeta').addEventListener('click', async () => {
  const { value: formValues, isConfirmed } = await Swal.fire({
    title: 'Pago con tarjeta',
    html: `
      <div class="text-start" style="max-width:360px; margin:0 auto; font-size:15px;">
        
        <!-- Número de tarjeta -->
        <label class="form-label mb-1">Número de tarjeta</label>
        <input id="sw_num" class="form-control mb-2" placeholder="Solo dígitos" inputmode="numeric">

        <!-- Mes/Año/CVV -->
        <div class="row g-2 mb-2">
          <div class="col-4">
            <label class="form-label mb-1">Mes</label>
            <input id="sw_mes" class="form-control" placeholder="MM" maxlength="2" inputmode="numeric">
          </div>
          <div class="col-4">
            <label class="form-label mb-1">Año</label>
            <input id="sw_ano" class="form-control" placeholder="YY" maxlength="2" inputmode="numeric">
          </div>
          <div class="col-4">
            <label class="form-label mb-1">CVV</label>
            <input id="sw_cvv" class="form-control" placeholder="***" maxlength="4" inputmode="numeric">
          </div>
        </div>

        <!-- Nombre -->
        <label class="form-label mb-1">Nombre en la tarjeta</label>
        <input id="sw_nom" class="form-control" placeholder="Como aparece en la tarjeta">
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: 'Pagar',
    cancelButtonText: 'Cancelar',
    preConfirm: () => {
      return {
        num: (document.getElementById('sw_num').value || '').trim(),
        mes: (document.getElementById('sw_mes').value || '').trim(),
        ano: (document.getElementById('sw_ano').value || '').trim(),
        cvv: (document.getElementById('sw_cvv').value || '').trim(),
        nom: (document.getElementById('sw_nom').value || '').trim(),
      };
    }
  });

  if (!isConfirmed) return;

  document.getElementById('metodo_pago').value  = 'Tarjeta';
  document.getElementById('tarjeta_numero').value = formValues.num;
  document.getElementById('tarjeta_mes').value    = formValues.mes;
  document.getElementById('tarjeta_anio').value   = formValues.ano;
  document.getElementById('tarjeta_cvv').value    = formValues.cvv;
  document.getElementById('tarjeta_nombre').value = formValues.nom;
  syncDireccion();
  document.getElementById('formPago').submit();
});




    

    document.getElementById('btnSinpe').addEventListener('click', async () => {
      const { value: ref, isConfirmed } = await Swal.fire({
        title: 'Pago por SINPE',
        html: `
          <div class="text-start">
            <p class="mb-2">Transferí el total vía SINPE Móvil <strong>XXXX-XXXX</strong></p>
            <input id="sw_sinpe" class="swal2-input" placeholder="Número Celular">
          </div>
        `,
        input: null,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => (document.getElementById('sw_sinpe').value || '').trim()
      });

      if (!isConfirmed) return;

      document.getElementById('metodo_pago').value = 'SINPE';
      document.getElementById('sinpe_ref').value   = ref;
      syncDireccion();
      document.getElementById('formPago').submit();
    });


    <?php if (!empty($errors)):
      $msg = implode('<br>', array_map('htmlspecialchars', $errors)); ?>
      Swal.fire({icon:'error', title:'Revisá la información', html:'<?= $msg ?>'});
    <?php endif; ?>
  </script>
</body>
</html>
