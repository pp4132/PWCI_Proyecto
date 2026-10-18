<?php
require_once __DIR__ . "/../../Controller/RegistroController.php";
$controlador = new RegistroController();

$resultado = $controlador->registrar();

$error = $resultado["error"];
$mensaje = $resultado["mensaje"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Crear cuenta | Academia</title>

    <!-- CSS general -->
    <link
        rel="stylesheet"
        href="../css/Style.css"
    >

    <!-- CSS de autenticación -->
    <link
        rel="stylesheet"
        href="../css/Auth.css"
    >
</head>


<body class="auth-page">


    <main class="auth">


        <!-- =================================
             SECCIÓN DEL FORMULARIO
        ================================== -->

        <section class="auth__form-section">

            <div class="auth__form-container">


                <!-- LOGO -->

                <a
                    href="../Public/Index.php"
                    class="auth__logo"
                >
                    ACADEMIA
                </a>


                <!-- ENCABEZADO -->

                <div class="auth__heading">

                    <span class="auth__tag">
                        COMIENZA A APRENDER
                    </span>

                    <h1>
                        Crea tu cuenta
                    </h1>

                    <p>
                        Únete a la plataforma y comienza
                        a desarrollar nuevas habilidades.
                    </p>

                </div>


                <!-- MENSAJE DE ERROR -->

                <?php if (!empty($error)): ?>

                    <div class="auth__error">

                        <?= htmlspecialchars($error) ?>

                    </div>

                <?php endif; ?>


                <!-- FORMULARIO -->

                <form
                    action=""
                    method="POST"
                    class="auth__form"
                >


                    <!-- =================================
                         NOMBRE
                    ================================== --> 
                    <div class="form-group">
                        <label for="nombre">Nombre(s)</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Tu nombre"
                            maxlength="100"
                            autocomplete="given-name"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input
                            type="text"
                            id="apellidos"
                            name="apellidos"
                            placeholder="Tus apellidos"
                            maxlength="150"
                            autocomplete="family-name"
                            required
                        >
                    </div>



                    <!-- =================================
                         CORREO
                    ================================== -->

                    <div class="form-group">

                        <label for="correo">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            placeholder="tu@correo.com"
                            autocomplete="email"
                            required
                        >

                    </div>


                    <!-- =================================
                         TIPO DE USUARIO
                    ================================== -->

                    <div class="form-group">

                        <label for="rol">
                            Quiero registrarme como
                        </label>

                        <select
                            id="rol"
                            name="rol"
                            required
                        >

                            <option
                                value=""
                                selected
                                disabled
                            >
                                Selecciona una opción
                            </option>

                            <option value="Estudiante">
                                Estudiante
                            </option>

                            <option value="Instructor">
                                Instructor
                            </option>

                        </select>

                    </div>

                    <!-- =================================
                         Género
                    ================================== -->

                    <div class="form-group">

                        <label for="rol">
                            Género
                        </label>

                        <select
                            id="genero"
                            name="genero"
                            required
                        >

                            <option
                                value=""
                                selected
                                disabled
                            >
                                Selecciona una opción
                            </option>

                            <option value="Hombre">
                                Hombre
                            </option>
                            <option value="Mujer">
                                Mujer
                            </option>
                            <option value="No contestar">
                                No contestar
                            </option>

                        </select>

                    </div>


                    <!-- =================================
                         CONTRASEÑA
                    ================================== -->

                    <div class="form-group">

                        <label for="password">
                            Contraseña
                        </label>


                        <div class="password-input">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Crea una contraseña"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="togglePassword"
                                aria-label="Mostrar contraseña"
                            >
                                Mostrar
                            </button>

                        </div>


                        <small class="form-help">
                            Utiliza al menos 8 caracteres.
                        </small>

                    </div>


                    <!-- =================================
                         CONFIRMAR CONTRASEÑA
                    ================================== -->

                    <div class="form-group">

                        <label for="password_confirmation">
                            Confirmar contraseña
                        </label>


                        <div class="password-input">

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repite tu contraseña"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                id="togglePasswordConfirmation"
                                aria-label="Mostrar contraseña"
                            >
                                Mostrar
                            </button>

                        </div>

                    </div>


                    <!-- =================================
                         TÉRMINOS
                    ================================== -->

                    <div class="form-check">

                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            required
                        >

                        <label for="terms">

                            Acepto los
                            <a href="#">
                                términos y condiciones
                            </a>

                        </label>

                    </div>


                    <!-- =================================
                         BOTÓN
                    ================================== -->

                    <button
                        type="submit"
                        class="btn btn--primary btn--large auth__submit"
                    >
                        Crear cuenta
                    </button>


                </form>


                <!-- =================================
                     LOGIN
                ================================== -->

                <div class="auth__register">

                    <span>
                        ¿Ya tienes una cuenta?
                    </span>

                    <a href="../Auth/Login.php">
                        Iniciar sesión
                    </a>

                </div>


                <!-- =================================
                     VOLVER
                ================================== -->

                <a
                    href="../Public/Index.php"
                    class="auth__back"
                >
                    ← Volver al inicio
                </a>


            </div>

        </section>


        <!-- =================================
             SECCIÓN VISUAL
        ================================== -->

        <section class="auth__visual">


            <div class="auth__visual-content">


                <div class="auth__visual-logo">
                    A
                </div>


                <span class="auth__visual-tag">
                    APRENDE. PRACTICA. AVANZA.
                </span>


                <h2>
                    Empieza algo nuevo
                    hoy.
                </h2>


                <p>
                    Crea tu cuenta, descubre nuevos cursos
                    y construye habilidades que puedas llevar
                    contigo.
                </p>


                <!-- =================================
                     TARJETA DECORATIVA
                ================================== -->

                <div class="auth__floating-card">

                    <div class="auth__floating-icon">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Aprendizaje personalizado
                        </strong>

                        <span>
                            Avanza a tu propio ritmo
                        </span>
                    </div>
                </div>


            </div>


        </section>


    </main>


    <!-- =================================
         JAVASCRIPT
    ================================== -->
    
    <script src="../js/Main.js">
        configurarPasswordToggle(
            "password",
            "togglePassword"
        );

        configurarPasswordToggle(
            "password_confirmation",
            "togglePasswordConfirmation"
        );

        /*
        * Validar que las contraseñas coincidan
        */
        const formularioRegistro =
            document.querySelector(".auth__form");

        const campoPassword =
            document.getElementById("password");

        const campoConfirmacion =
            document.getElementById("password_confirmation");

        if (
            formularioRegistro &&
            campoPassword &&
            campoConfirmacion
        ) {
            formularioRegistro.addEventListener(
                "submit",
                function (event) {
                    if (
                        campoPassword.value !==
                        campoConfirmacion.value
                    ) {
                        event.preventDefault();

                        campoConfirmacion.setCustomValidity(
                            "Las contraseñas no coinciden."
                        );

                        campoConfirmacion.reportValidity();
                    } else {
                        campoConfirmacion.setCustomValidity("");
                    }
                }
            );
        }
    </script>


</body>

</html>