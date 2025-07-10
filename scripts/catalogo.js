document.addEventListener('DOMContentLoaded', function () {

  const productos = [
    { id: 1, nombre: "Llanta", descripcion: "Llanta para moto", precio: 30000 },
    { id: 2, nombre: "Casco", descripcion: "Casco negro con visor", precio: 45000 },
    { id: 3, nombre: "Aceite", descripcion: "Aceite para motor", precio: 8000 }
  ];

  const catalogoList = document.getElementById("catalogo-list");

  productos.forEach(function (producto) {
    const card = document.createElement("div");
    card.className = "col-md-4 mb-3";
    card.innerHTML = `
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">${producto.nombre}</h5>
          <p class="card-text">${producto.descripcion}</p>
          <p><strong>₡${producto.precio}</strong></p>
          <button class="btn btn-danger agregar-carrito" data-id="${producto.id}">Agregar al carrito</button>
        </div>
      </div>
    `;
    catalogoList.appendChild(card);
  });

  catalogoList.addEventListener("click", function (e) {
    if (e.target.classList.contains("agregar-carrito")) {
      const id = parseInt(e.target.dataset.id);
      const producto = productos.find(p => p.id === id);

      let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
      carrito.push(producto);
      localStorage.setItem("carrito", JSON.stringify(carrito));

      alert("Producto agregado al carrito");
    }
  });
});