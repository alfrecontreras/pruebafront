<?php
function fetchWithBearerAuthorization($url, $token)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $token
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false) {
        return 'Error al realizar la solicitud: ' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);
        if ($responseData === null) {
            return 'Error al decodificar la respuesta JSON';
        } else {
            //return $responseData;
            return $response;
        }
    }
    // Cerrar la sesión cURL
    curl_close($ch);
}

// Uso de la función
$urlSobrenosotros = 'localhost/pruebaback/v1/sobrenosotros/';
$tokenget = 'get';
$responseDataSobreNosotros = fetchWithBearerAuthorization($urlSobrenosotros, $tokenget);
//var_dump($responseData);
// Convertir la respuesta a JSON
$jsonResponse = json_encode($responseDataSobreNosotros);

$urlJugadores = 'localhost/pruebaback/v1/jugadores/';
$responseDataJugadores = fetchWithBearerAuthorization($urlJugadores, $tokenget);
$endpointNuestrosJugadores  = json_encode($responseDataJugadores);

$urlProxEntrenamientos = 'localhost/pruebaback/v1/proxentrenamientos/';
$responseProxEntrenamientos = fetchWithBearerAuthorization($urlProxEntrenamientos, $tokenget);
$jsonProxEntrenamientos = json_encode($responseProxEntrenamientos);

$urlSociales = 'localhost/pruebaback/v1/rrss/';
$responseSociales = fetchWithBearerAuthorization($urlSociales, $tokenget);
$jsonSociales = json_encode($responseSociales);

$urlCarrusel = 'localhost/pruebaback/v1/carrusel/';
$responseCarrusel = fetchWithBearerAuthorization($urlCarrusel, $tokenget);
$jsonCarrusel = json_encode($responseCarrusel);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="./src/script/validacion.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./src/css/font-awesome.min.css">
    <style>
        .carousel-item {
            height: 800px;
        }

        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .carousel-caption {
            color: white;
            text-align: center;
        }

        @media (max-width: 768px) {
            .carousel-caption {
                top: 70%;
                transform: translateY(-70%);
                font-size: 14px;
            }

            .carousel-caption h5 {
                font-size: 16px;
            }

            .carousel-caption p {
                font-size: 12px;
            }
        }

        .custom-scrollable-cards {
            max-height: 400px;
            overflow-y: auto;
        }

        .custom-card {
            margin-bottom: 15px;
        }

        .custom-card-body {
            display: flex;
            align-items: center;
        }

        .custom-card-body img {
            border-radius: 50%;
            margin-right: 15px;
        }

        .star-rating .fa {
            color: gold;
        }

        #imagensobrenosotros {
            text-align: -webkit-center;
        }

        /* Estilo base para los jugadores */
        .tp-player {
            display: flex;
            flex-direction: column;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        /* Estilo para cuando se pasa el mouse encima */
        .tp-player.expand {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }

        /* Estilo para ocultar detalles adicionales */
        .tp-player .details {
            display: none;
        }

        /* Estilo para mostrar detalles adicionales cuando se expande */
        .tp-player.expand .details {
            display: block;
        }

        /* Estilo para la imagen y nombre */
        .tp-player .summary {
            display: flex;
            align-items: center;
        }

        /* Estilo para la imagen */
        .tp-player .summary img {
            border-radius: 50%;
            margin-right: 10px;
        }

        /* Estilo para el nombre y posición */
        .tp-player .summary div {
            flex-grow: 1;
        }

        /* Estilo para las estrellas */
        .tp-stars .fa {
            color: gold;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DESPLAZARSE
            function scrollToElement(element) {
                window.scroll({
                    behavior: 'smooth',
                    left: 0,
                    top: element.offsetTop
                });
            }

            // MANEJAR CLICKS EN EL NAVBAR
            document.querySelectorAll('.navbar-nav a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    console.log("entra??");
                    e.preventDefault();

                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        scrollToElement(targetElement);
                    }
                });
            });
            //CARGAR SOBRE NOSOTROS
            var responseData = JSON.parse(<?php echo $jsonResponse; ?>);
            responseData.data.forEach(item => {
                document.getElementById("sobrenosotros").innerHTML = '<h5>' + item.descripcion + '</h5>';
                document.getElementById("imagensobrenosotros").innerHTML = '<img src="' + item.logo_color + '" class="d-block w-50" alt="...">';
            });
            //CARGAR JUGADORES
            const dataJugadores = JSON.parse(<?php echo $endpointNuestrosJugadores; ?>);
            const container = document.getElementById('mCSB_2_container');
            const estrellacolor = '<i class="fa fa-star"></i>';
            const estrellablanca = '<i class="fa fa-star-o"></i>';
            dataJugadores.data.forEach(item => {
                if (item.activo) {
                    let estrellas = '';
                    const random = Math.floor(Math.random() * 6);
                    for (let i = 0; i < 5; i++) {
                        estrellas += (i < random) ? estrellacolor : estrellablanca;
                    }

                    let redes_sociales = '';
                    if (item.redes_sociales) {
                        redes_sociales = `<a href="${item.redes_sociales.rrss_valor}" target="_blank"><i class="${item.redes_sociales.rrss_icono}"></i> ${item.redes_sociales.rrss_nombre}</a>`;
                    }

                    const playerContainer = document.createElement('div');
                    playerContainer.classList.add('tp-player');

                    const summary = document.createElement('div');
                    summary.classList.add('summary');

                    const img = document.createElement('img');
                    img.src = './src/images/foto.png';
                    img.alt = 'Foto';
                    img.width = 70;
                    img.height = 70;

                    const info = document.createElement('div');

                    const name = document.createElement('h5');
                    name.classList.add('card-title');
                    name.textContent = item.personal.nombre;

                    const position = document.createElement('p');
                    position.classList.add('card-text');
                    const positionBadge = document.createElement('span');
                    positionBadge.classList.add('badge', 'bg-secondary');
                    positionBadge.textContent = item.posicion.nombre.toUpperCase();
                    position.appendChild(positionBadge);

                    const stars = document.createElement('div');
                    stars.classList.add('star-rating');
                    stars.innerHTML = estrellas;

                    info.appendChild(name);
                    info.appendChild(position);
                    info.appendChild(stars);

                    summary.appendChild(img);
                    summary.appendChild(info);

                    playerContainer.appendChild(summary);

                    const details = document.createElement('div');
                    details.classList.add('details');

                    const profession = document.createElement('p');
                    profession.classList.add('card-text');
                    profession.textContent = item.personal.profesion;

                    const socialIcons = document.createElement('p');
                    socialIcons.classList.add('card-text');
                    socialIcons.innerHTML = redes_sociales;

                    details.appendChild(profession);
                    details.appendChild(socialIcons);

                    playerContainer.appendChild(details);

                    container.appendChild(playerContainer);

                    playerContainer.addEventListener('mouseover', function() {
                        playerContainer.classList.add('expand');
                    });

                    playerContainer.addEventListener('mouseout', function() {
                        playerContainer.classList.remove('expand');
                    });
                }
            });

            //CARGAR PROXIMOS ENTRENAMIENTOS
            var responseDataEntrenamientos = JSON.parse(<?php echo $jsonProxEntrenamientos; ?>);
            console.log(responseDataEntrenamientos);
            var textoprox = '';
            responseDataEntrenamientos.data.forEach(item => {
                if (item.activo) {
                    textoprox = textoprox + '<li>Fecha: ' + item.momento.fecha + ' - Horario:' + item.momento.horario + ' - Lugar: ' + item.lugar.nombre + " - Dirección: " + item.lugar.direccion.calle + '</li>';

                }
            });
            document.getElementById("textoproxentrenamientos").innerHTML = textoprox;

            //CARGAR REDES SOCIALES
            var responseDataSociales = JSON.parse(<?php echo $jsonSociales; ?>);
            console.log(responseDataSociales);
            var textosociales = '<h4>Redes Sociales</h4> ';
            responseDataSociales.data.forEach(item => {
                if (item.activo) {
                    textosociales = textosociales + '<p><a href="' + item.valor + '" target="_blank"><i class="' + item.icono + '"></i> ' + item.nombre + '</a></p>';
                }
            });
            document.getElementById("redes-sociales").innerHTML = textosociales;

            //CARGAR CARRUSEL
            var responseDataCarrusel = JSON.parse(<?php echo $jsonCarrusel; ?>);
            console.log(responseDataCarrusel);
            var textocarrusel = '';
            var i = 1;
            responseDataCarrusel.data.forEach(item => {
                if (item.activo) {
                    if (i == 1) {
                        var clase = 'carousel-item active';
                    } else {
                        var clase = 'carousel-item';
                    }
                    textocarrusel = textocarrusel + '<div class="' + clase + '"><img src="' + item.imagen + '" class="d-block w-100" alt="..."><div class="carousel-caption d-md-block"><h5>' + item.titulo + '</h5><p>' + item.descripcion + '</p></div></div>';
                    i++;
                }
            });
            document.getElementById("fotoscarrusel").innerHTML = textocarrusel;



            var formulario = document.getElementById('formulario');
            var nombreCompleto = document.getElementById('nombrecompleto');
            var correoElectronico = document.getElementById('correoelectronico');
            var telefono = document.getElementById('telefono');
            var mensaje = document.getElementById('mensaje');

            nombreCompleto.addEventListener('blur', validarNombreCompleto);
            correoElectronico.addEventListener('blur', validarCorreoElectronico);
            telefono.addEventListener('blur', validarTelefono);
            mensaje.addEventListener('blur', validarMensaje);

            nombreCompleto.addEventListener('input', validarNombreCompleto);
            correoElectronico.addEventListener('input', validarCorreoElectronico);
            telefono.addEventListener('input', validarTelefono);
            mensaje.addEventListener('input', validarMensaje);

            function validarNombreCompleto() {
                if (nombreCompleto.value.trim() === '') {
                    mostrarError(nombreCompleto, 'Por favor ingresa tu nombre completo.');
                    return false;
                } else {
                    mostrarError(nombreCompleto, '');
                    return true;
                }
            }

            function validarCorreoElectronico() {
                var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (correoElectronico.value.trim() === '') {
                    mostrarError(correoElectronico, 'Por favor ingresa tu correo electrónico.');
                    return false;
                } else if (!regexCorreo.test(correoElectronico.value.trim())) {
                    mostrarError(correoElectronico, 'Por favor ingresa un correo electrónico válido.');
                    return false;
                } else {
                    mostrarError(correoElectronico, '');
                    return true;
                }
            }

            function validarTelefono() {
                var regexTelefono = /^\d{9}$/;
                if (telefono.value.trim() === '') {
                    mostrarError(telefono, 'Por favor ingresa tu número de teléfono.');
                    return false;
                } else if (!regexTelefono.test(telefono.value.trim())) {
                    mostrarError(telefono, 'Por favor ingresa un número de teléfono válido de 9 dígitos.');
                    return false;
                } else {
                    mostrarError(telefono, '');
                    return true;
                }
            }

            function validarMensaje() {
                if (mensaje.value.trim() === '') {
                    mostrarError(mensaje, 'Por favor ingresa un mensaje.');
                    return false;
                } else if (mensaje.value.trim().length < 10) {
                    mostrarError(mensaje, 'Porfavor ingresa un mensaje con 10 caracteres al menos.');
                    return false;
                } else if (mensaje.value.includes("<") || mensaje.value.includes(">") || mensaje.value.includes("?")) {
                    mostrarError(mensaje, "Por favor elimina los caracteres especiales '<', '>' o '?' de tu mensaje.");
                    return false;
                } else {
                    mostrarError(mensaje, "");
                    return true;
                }
            }

            function validarCaptcha() {
                var recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length === 0) {
                    document.getElementById("errorcaptcha").style.display = "block";
                    return false;
                } else {
                    document.getElementById("errorcaptcha").style.display = "none";
                    return true;
                }
            }

            function mostrarError(elemento, mensaje) {
                var feedback = elemento.nextElementSibling;
                feedback.textContent = mensaje;

                if (mensaje) {
                    elemento.classList.add('is-invalid');
                    elemento.classList.remove('is-valid');
                } else {
                    elemento.classList.remove('is-invalid');
                    elemento.classList.add('is-valid');
                }
            }

            formulario.addEventListener('submit', function(event) {
                event.preventDefault();
                var validacion1 = validarNombreCompleto();
                var validacion2 = validarCorreoElectronico();
                var validacion3 = validarTelefono();
                var validacion4 = validarMensaje();
                var validacion5 = validarCaptcha();

                if (validacion1 && validacion2 && validacion3 && validacion4 && validacion5) {
                    var recaptchaResponse = grecaptcha.getResponse();
                    var recaptchaInput = document.createElement('input');
                    recaptchaInput.setAttribute('type', 'hidden');
                    recaptchaInput.setAttribute('name', 'g-recaptcha-response');
                    recaptchaInput.setAttribute('value', recaptchaResponse);
                    this.appendChild(recaptchaInput);
                    this.submit();
                }
            });
        });
    </script>
    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <div class="navbar-brand mx-auto d-lg-none">
                <a href="#inicio">
                    <img src="https://fc.sonkei.cl/images/logo_v2.webp" alt="" width="45" height="45">
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#nuestrosjugadores">Equipo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Nuestra Historia</a>
                    </li>
                </ul>
                <div class="navbar-brand d-none d-lg-block mx-auto">
                    <a href="#inicio">
                        <img src="https://fc.sonkei.cl/images/logo_v2.webp" alt="" width="45" height="45">
                    </a>
                </div>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#proxentrenamientos">Entrenamiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</head>

<body>
    <div id="inicio"></div>
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="container-fluid">
            <div id="fotoscarrusel" class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./src/images/1.jpeg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <h5>NOMBRE JUGADOR</h5>
                        <p>DETALLE</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./src/images/1.jpeg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <h5>NOMBRE JUGADOR</h5>
                        <p>DETALLE</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./src/images/1.jpeg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-md-block">
                        <h5>NOMBRE JUGADOR</h5>
                        <p>DETALLE</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="container-fluid mt-4 mb-4" id="contacto">
        <div class="card">
            <div class="card-header">
                <h4>Contacto</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div id="formcontacto">
                            <form id="formulario" class="needs-validation" novalidate>
                                <div class="form-group mb-3">
                                    <label for="nombrecompleto" class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control" id="nombrecompleto" required>
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu nombre completo.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="correoelectronico" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="correoelectronico">
                                    <div class="invalid-feedback">
                                        Por favor ingresa un email válido.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" pattern="[0-9]{9}" class="form-control" id="telefono" required>
                                    <div class="invalid-feedback">
                                        Por favor ingresa un telefono válido de 9 dígitos.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="mensaje" class="form-label">Mensaje</label>
                                    <textarea class="form-control" id="mensaje" required></textarea>
                                    <div class="invalid-feedback">
                                        Por favor ingresa un mensaje.
                                    </div>
                                </div>
                                <div class="g-recaptcha mb-3" data-sitekey="6Lc0ol4fAAAAAN5x4WHVhqfg4LyGKdhLSURJ4lKz"></div>
                                <div id="errorcaptcha" class="mb-2" style="display: none;">
                                    <h4 style="color: red">Porfavor complete el captcha.</h4>
                                </div>
                                <div class="d-grid gap-2 col-6 mb-5">
                                    <button id="btnEnviar" type="submit" class="btn btn-lg btn-primary" type="button">Enviar</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mb-4" id="proxentrenamientos">
        <div class="card">
            <div class="card-header">
                <h4>Próximos entrenamientos</h4>
            </div>
            <div class="card-body">
                <ul id="textoproxentrenamientos">
                    <li>FECHA- HORA - LUGAR</li>
                </ul>
            </div>
        </div>
    </div>



    <!-- <div id="nuestrosjugadores">
        <h5 class="text-center">Nuestros Jugadores</h5>
        <div class="container mt-3 mb-5 border">
            <div id="contenedorjugadores" class="custom-scrollable-cards">
                <div class="custom-card card mt-3">
                    <div class="custom-card-body card-body">
                        <img src="./src/images/foto.png" alt="Foto" width="70" height="70">
                        <div>
                            <h5 class="card-title">SEBASTIÁN CABEZAS 1</h5>
                            <p class="card-text"><span class="badge bg-secondary">ARQUERO</span></p>
                            <div class="star-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <p class="card-text">INGENIERO AGRONOMO</p>
                            <p class="card-text">
                            <p><a href="#" target="_blank"><i class="fa fa-facebook"></i> Facebook</a></p>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="custom-card card">
                    <div class="custom-card-body card-body">
                        <img src="./src/images/foto.png" alt="Foto" width="70" height="70">
                        <div>
                            <h5 class="card-title">SEBASTIÁN CABEZAS 2</h5>
                            <p class="card-text"><span class="badge bg-secondary">DEFENSA</span></p>
                            <div class="star-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <p class="card-text">INGENIERO AGRONOMO</p>
                            <p class="card-text">
                            <p><a href="#" target="_blank"><i class="fa fa-facebook"></i> Facebook</a></p>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="custom-card card">
                    <div class="custom-card-body card-body">
                        <img src="./src/images/foto.png" alt="Foto" width="70" height="70">
                        <div>
                            <h5 class="card-title">SEBASTIÁN CABEZAS 3</h5>
                            <p class="card-text"><span class="badge bg-secondary">CENTRO DELANTERO</span></p>
                            <div class="star-rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <p class="card-text">INGENIERO AGRONOMO</p>
                            <p class="card-text">
                            <p><a href="#" target="_blank"><i class="fa fa-facebook"></i> Facebook</a></p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div id="nuestrosjugadores">
        <h5 class="text-center">Nuestros Jugadores</h5>
        <div class="container mt-3 mb-5 border">
            <div id="mCSB_2_container" class="custom-scrollable-cards">
                <!-- Los jugadores se agregarán aquí dinámicamente -->
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4> Palabras Claves</h4>
                    <p>
                    <h5>FUTBOL</h5>
                    </p>
                    <p>
                    <h5>BALON</h5>
                    </p>
                    <p>
                    <h5>CLUB</h5>
                    </p>
                    <p>
                    <h5>CHILE</h5>
                    </p>

                </div>
                <div class="col-md-4">
                    <div id="imagensobrenosotros"></div>
                    <p id="sobrenosotros"></p>
                    <p><a href="#" target="_blank"><i class="fa fa-home"></i> Av. Providencia 1234, Oficina 601 <br>
                            Providencia, Santiago <br>
                            Chile.</a></p>
                    <hr>
                    <p><a href="#" target="_blank"><i class="fa fa-phone"></i> +56 2 0000 0000</a></p>
                    <hr>
                    <p><a href="#" target="_blank"><i class="fa fa-envelope"></i> info@test.cl</a></p>
                </div>
                <div id="redes-sociales" class="col-md-4">

                </div>
            </div>
        </div>
    </footer>
</body>

</html>