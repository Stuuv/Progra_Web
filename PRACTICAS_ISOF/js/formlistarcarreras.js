// Obtener los datos del archivo PHP (que devuelve JSON)
fetch("../php/carreras/listar.php")
.then((response) => response.json())
.then((data) => {
  const tablaBody = document.querySelector("tbody");
  if (data.length === 0) {
    tablaBody.innerHTML =
      "<tr><td colspan='3'>No se encontraron registros.</td></tr>";
  } else {
    data.forEach((carreras) => {
      const row = tablaBody.insertRow();
      const id_carreraCell = row.insertCell();
      const nombreCell = row.insertCell();
      const direccionCell = row.insertCell();

      id_carreraCell.textContent = carreras.id_carrera;
      nombreCell.textContent = carreras.nombre;
      direccionCell.textContent = carreras.direccion;
    });
  }
});

// Variable para controlar si es edición
let editando = false;

// Cargar tabla inicial
function cargarTabla() {
  fetch("../php/carreras/listar.php")
    .then((response) => response.json())
    .then((data) => {
      const tablaBody = document.getElementById("tablacarrerasBody");
      tablaBody.innerHTML = "";

      if (data.length === 0) {
        tablaBody.innerHTML =
          "<tr><td colspan='4'>No se encontraron registros.</td></tr>";
      } else {
        data.forEach((carreras) => {
          const row = tablaBody.insertRow();
          row.innerHTML = `
                          <td>${carreras.id_carrera}</td>
                          <td>${carreras.nombre}</td>
                          <td>
                              <button onclick="editarcarreras('${carreras.id_carrera}')">Modificar</button>
                              <button onclick="eliminarcarreras('${carreras.id_carrera}')">Borrar</button>
                          </td>
                      `;
        });
      }
    });
}

// Mostrar modal para editar
function editarcarreras(id_carrera) {
  editando = true;
  fetch(`../php/carreras/obtener.php?id_carrera=${id_carrera}`)
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("modalTitulo").textContent = "Editar carrera";
      document.getElementById("id_editar").value = data.id_carrera;
      document.getElementById("nombrecarrera").value = data.nombre; // Corrección aquí
      document.getElementById("modalFormulario").style.display = "flex";
    });
}


// Eliminar carreras
function eliminarcarreras(id_carrera) {
  if (confirm("¿Estás seguro de eliminar esta carrera?")) {
    fetch("../php/carreras/eliminar.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id_carrera=${encodeURIComponent(id_carrera)}`,  // <-- Se envía en el cuerpo
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Carrera eliminada con éxito");
        cargarTabla();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(error => console.error("Error:", error));
  }
}


// Mostrar modal para agregar
function mostrarModalAgregar() {
  editando = false;
  document.getElementById("modalTitulo").textContent =
    "Agregar Nuevo carreras";
  document.getElementById("formulariocarreras").reset();
  document.getElementById("modalFormulario").style.display = "flex";
}

// Cerrar modal
function cerrarModal() {
  document.getElementById("modalFormulario").style.display = "none";
}

// Guardar carreras (nuevo o edición)
function guardarcarreras(event) {
  event.preventDefault();

  const data = {
    id_carrera: document.getElementById("id_editar").value,
    nombre: document.getElementById("nombrecarrera").value
  };

  const url = editando ? "../php/carreras/actualizar.php" : "../php/carreras/grabar.php";
  const method = editando ? "POST" : "POST";

  fetch(url, {
    method: method,
    headers: {
      "Content-Type": "application/json", // Cambiado a JSON
    },
    body: JSON.stringify(data), // Enviar datos como JSON
  })
  .then(response => response.json())
  .then(() => {
    cerrarModal();
    cargarTabla();
  })
  .catch(error => console.error("Error:", error));
}


// Cargar tabla al iniciar
cargarTabla();

// Cerrar modal al hacer click fuera
  window.onclick = function(event) {
      const modal = document.getElementById('modalFormulario');
      if (event.target == modal) {
          cerrarModal();
      }
  }
// Variables de paginación
let paginaActual = 1;
let registrosPorPagina = 10;
let datoscarreras = [];

// Cargar tabla inicial
function cargarTabla() {
  fetch("../php/carreras/listar.php")
    .then((response) => response.json())
    .then((data) => {
      datoscarreras = data;
      renderizarTabla();
      actualizarControlesPaginacion();
    });
}

// Renderizar tabla con paginación
function renderizarTabla() {
  const inicio = (paginaActual - 1) * registrosPorPagina;
  const fin = inicio + registrosPorPagina;
  const carrerasPagina = datoscarreras.slice(inicio, fin);

  const tablaBody = document.getElementById("tablacarrerasBody");
  tablaBody.innerHTML = "";

  if (carrerasPagina.length === 0) {
    tablaBody.innerHTML =
      "<tr><td colspan='4'>No se encontraron registros.</td></tr>";
  } else {
    carrerasPagina.forEach((carreras) => {
      const row = tablaBody.insertRow();
      row.innerHTML = `
                  <td>${carreras.id_carrera}</td>
                  <td>${carreras.nombre}</td>
                  <td>
                      <button class="btn-modificar" onclick="editarcarreras('${carreras.id_carrera}')">Modificar</button>
                      <button class="btn-borrar" onclick="eliminarcarreras('${carreras.id_carrera}')">Borrar</button>
                  </td>
              `;
    });
  }
}

// Actualizar controles de paginación
function actualizarControlesPaginacion() {
  const totalPaginas = Math.ceil(
    datoscarreras.length / registrosPorPagina
  );
  const controles = document.getElementById("controlesPaginacion");
  controles.innerHTML = "";

  // Botón Anterior
  if (paginaActual > 1) {
    const botonAnterior = document.createElement("button");
    botonAnterior.innerText = "Anterior";
    botonAnterior.addEventListener("click", () => {
      paginaActual--;
      renderizarTabla();
      actualizarControlesPaginacion();
    });
    controles.appendChild(botonAnterior);
  }

  // Números de página
  for (let i = 1; i <= totalPaginas; i++) {
    const botonPagina = document.createElement("button");
    botonPagina.innerText = i;
    botonPagina.disabled = i === paginaActual;
    botonPagina.addEventListener("click", () => {
      paginaActual = i;
      renderizarTabla();
      actualizarControlesPaginacion();
    });
    controles.appendChild(botonPagina);
  }

  // Botón Siguiente
  if (paginaActual < totalPaginas) {
    const botonSiguiente = document.createElement("button");
    botonSiguiente.innerText = "Siguiente";
    botonSiguiente.addEventListener("click", () => {
      paginaActual++;
      renderizarTabla();
      actualizarControlesPaginacion();
    });
    controles.appendChild(botonSiguiente);
  }
}

// Cambiar cantidad de registros por página
function cambiarRegistrosPorPagina(valor) {
  registrosPorPagina = parseInt(valor);
  paginaActual = 1;
  renderizarTabla();
  actualizarControlesPaginacion();
}