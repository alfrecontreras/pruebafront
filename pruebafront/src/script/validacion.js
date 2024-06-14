function ValidarFormulario() {
    var nombreCompleto = document.getElementById("nombrecompleto");
    var correo = document.getElementById("correoelectronico");
    var telefono = document.getElementById("telefono");
    var mensaje = document.getElementById("mensaje");

    if (!nombreCompleto.value.trim()) {
        mostrarError(nombreCompleto, "Por favor ingresa tu nombre completo.");
        return false;
    } else {
        mostrarError(nombreCompleto, "");
    }

    var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correo.value.trim()) {
        mostrarError(correo, "Por favor ingresa tu correo electrónico.");
        return false;
    } else if (!regexCorreo.test(correo.value.trim())) {
        mostrarError(correo, "Por favor ingresa un correo electrónico válido.");
        return false;
    } else {
        mostrarError(correo, "");
    }

    var regexTelefono = /^\d{9}$/;
    if (!telefono.value.trim()) {
        mostrarError(telefono, "Por favor ingresa tu número de teléfono.");
        return false;
    } else if (!regexTelefono.test(telefono.value.trim())) {
        mostrarError(telefono, "Por favor ingresa un número de teléfono válido de 9 dígitos.");
        return false;
    } else {
        mostrarError(telefono, "");
    }

    if (mensaje.value.trim().length < 10) {
        //mostrarError(mensaje, "Por favor ingresa un mensaje con al menos 10 caracteres.");
        document.getElementById("errorMensaje").style.visibility=true;
        return false;
    } else if (mensaje.value.includes("<") || mensaje.value.includes(">") || mensaje.value.includes("?")) {
        mostrarError(mensaje, "Por favor elimina los caracteres especiales '<', '>' o '?' de tu mensaje.");
        return false;
    } else {
        mostrarError(mensaje, "");
    }

    // Si todas las validaciones pasan, el formulario se puede enviar
    return true;
}

function mostrarError(elemento, mensaje) {
    var feedback = elemento.nextElementSibling;
    feedback.textContent = mensaje;
}