document.addEventListener("DOMContentLoaded", () => {
  const fechaElemento = document.getElementById("fecha-actual");
  if (fechaElemento) {
    const hoy = new Date();
    const opciones = { year: "numeric", month: "2-digit", day: "2-digit" };
    const fechaFormateada = hoy.toLocaleDateString("es-CO", opciones);
    fechaElemento.textContent = fechaFormateada;
  }
});