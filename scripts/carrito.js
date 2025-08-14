document.addEventListener('DOMContentLoaded', () => {

  const params = new URLSearchParams(location.search);
  const ok = (params.get('ok') || '').toLowerCase();
  const pedidoId = params.get('id');

  if (ok) {
    const map = {
      agregado: { icon: 'success', title: 'Producto agregado al carrito' },
      quitado: { icon: 'warning', title: 'Producto eliminado' },
      vaciar: { icon: 'info', title: 'Carrito vaciado' },
      pedido: { icon: 'success', title: pedidoId ? `¡Pedido #${pedidoId} creado!` : '¡Pedido creado con éxito!' },
      error: { icon: 'error', title: 'Ocurrió un error' },
      errorbd: { icon: 'error', title: 'Error de conexión' },
      vacio: { icon: 'info', title: 'El carrito está vacío' }
    };
    const cfg = map[ok];
    if (cfg) {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: cfg.icon,
        title: cfg.title,
        showConfirmButton: false,
        timer: 2200,
        timerProgressBar: true
      });

      const url = new URL(location.href);
      url.searchParams.delete('ok');
      url.searchParams.delete('id');
      history.replaceState({}, '', url.toString());
    }
  }


  document.querySelectorAll('form.needs-confirm').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const accion = form.dataset.accion || '';
      const texts = {
        quitar: '¿Quitar este producto del carrito?',
        vaciar: '¿Vaciar todo el carrito?',
        finalizar: '¿Confirmar y crear el pedido?'
      };
      const res = await Swal.fire({
        title: 'Confirmar',
        text: texts[accion] || '¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        reverseButtons: true
      });
      if (res.isConfirmed) form.submit();
    });
  });

 
});
