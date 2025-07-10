document.addEventListener('DOMContentLoaded', function () {
  const lista = document.getElementById("carrito-list");
  let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

  if (carrito.length === 0) {
    lista.innerHTML = "<p class='text-center'>El carrito está vacío.</p>";
    return;
  }

  carrito.forEach(function (item, index) {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-3";
    card.innerHTML = `
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">${item.nombre}</h5>
          <p class="card-text">${item.descripcion}</p>
          <p><strong>₡${item.precio}</strong></p>
          <button class="btn btn-secondary eliminar-item" data-index="${index}">Eliminar</button>
        </div>
      </div>
    `;
    lista.appendChild(card);
  });

  lista.addEventListener("click", function (e) {
    if (e.target.classList.contains("eliminar-item")) {
      const index = parseInt(e.target.dataset.index);
      carrito.splice(index, 1);
      localStorage.setItem("carrito", JSON.stringify(carrito));
      location.reload();
    }
  });
});