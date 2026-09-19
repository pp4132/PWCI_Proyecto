<?php
    /*
     * En el futuro, estos valores pueden venir del Controller.
     *
     * Ejemplo:
     * $error = "Correo o contraseña incorrectos.";
     *
     * Por ahora lo dejamos vacío para el prototipo.
     */

    $error = "";
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Iniciar sesión | Academia</title>

    <!-- CSS general -->
    <link
        rel="stylesheet"
        href="../css/Style.css"
    >

    <!-- CSS específico del Login -->
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
                        BIENVENIDO DE NUEVO
                    </span>

                    <h1>
                        Iniciar sesión
                    </h1>

                    <p>
                        Continúa aprendiendo
                        donde lo dejaste.
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


                    <!-- CORREO -->

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


                    <!-- CONTRASEÑA -->

                    <div class="form-group">

                        <div class="form-group__header">

                            <label for="password">
                                Contraseña
                            </label>

                            <a href="#">
                                ¿Olvidaste tu contraseña?
                            </a>

                        </div>


                        <div class="password-input">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
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

                    </div>


                    <!-- RECORDAR -->

                    <div class="form-check">

                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                        >

                        <label for="remember">
                            Mantener sesión iniciada
                        </label>

                    </div>


                    <!-- BOTÓN -->

                    <button
                        type="submit"
                        class="btn btn--primary btn--large auth__submit"
                    >
                        Iniciar sesión
                    </button>


                </form>


                <!-- REGISTRO -->

                <div class="auth__register">

                    <span>
                        ¿Todavía no tienes una cuenta?
                    </span>

                    <a href="../Auth/Registro.php">
                        Crear una cuenta
                    </a>

                </div>


                <!-- VOLVER -->

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
                    Tu siguiente habilidad
                    comienza aquí.
                </h2>


                <p>
                    Accede a tus cursos, revisa tu progreso
                    y continúa construyendo nuevas habilidades.
                </p>


                <!-- TARJETA DECORATIVA -->

                <div class="auth__floating-card">

                    <div class="auth__floating-icon">
                        ✓
                    </div>

                    <div>

                        <strong>
                            Tu progreso
                        </strong>

                        <span>
                            Sigue avanzando
                        </span>

                    </div>

                </div>


            </div>


        </section>


    </main>


    <!-- JavaScript -->

    <script src="../js/Main.js"></script>

</body>

</html>