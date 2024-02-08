function aceptarCookies() {
    var fechaExpiracion = new Date();
    fechaExpiracion.setDate(fechaExpiracion.getDate() + 30);

    document.cookie = "aceptaCookies=true; expires=" + fechaExpiracion.toUTCString() + "; path=/";

    // Mostrar un alert antes de redirigir al usuario, que confirme que hemos aceptado las cookies
    alert("Has aceptado las cookies correctamente. La aceptación caducará en 30 días.");

    window.history.back();
}