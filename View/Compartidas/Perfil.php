<?php

require_once __DIR__ . '/../../config.php';

/*
 * Los datos llegan desde PerfilController.php.
 */

$usuario = $datos['usuario'] ?? [];

$nombre = $usuario['nombre'] ?? '';
$apellido = $usuario['apellido'] ?? '';
$correo = $usuario['correo'] ?? '';
$telefono = $usuario['telefono'] ?? '';
$rol = $usuario['rol'] ?? '';

$inicial = strtoupper(
    substr($nombre, 0, 1)
);
?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Mi perfil | Academia
    </title>


    <!-- CSS GENERAL -->
    <link rel="stylesheet"
        href="<?= url('View/css/Style.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/Tablero.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/Perfil.css') ?>">

</head>


<body class="dashboard-body">


<div class="dashboard">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <?php require_once __DIR__ . "/SideBar.php"; ?>

    <!-- =====================================================
         CONTENIDO PRINCIPAL
    ====================================================== -->

    <main class="dashboard-main">


        <!-- =================================================
             HEADER
        ================================================== -->

        <header class="dashboard-header">


            <button
                type="button"
                class="mobile-menu"
                id="mobileMenu"
            >
                ☰
            </button>


            <div class="dashboard-header__search">

                <span>
                    🔍
                </span>


                <input
                    type="search"
                    placeholder="Buscar cursos..."
                >

            </div>


            <div class="dashboard-header__actions">


                <button
                    type="button"
                    class="notification-button"
                    aria-label="Notificaciones"
                >

                    ♢

                    <span>
                        2
                    </span>

                </button>


                <div class="header-user">


                    <div class="header-user__avatar">

                        <?= htmlspecialchars($inicial) ?>

                    </div>


                    <div>

                        <strong>

                            <?= htmlspecialchars($nombre) ?>

                        </strong>


                        <span>

                            <?= htmlspecialchars($rol) ?>

                        </span>

                    </div>


                </div>


            </div>


        </header>



        <!-- =================================================
             CONTENIDO DEL PERFIL
        ================================================== -->

        <div class="dashboard-content">


            <!-- ENCABEZADO -->

            <section class="profile-heading">


                <div>

                    <span class="section-tag">
                        CUENTA
                    </span>


                    <h1>
                        Mi perfil
                    </h1>


                    <p>
                        Consulta y administra tu información personal.
                    </p>

                </div>


            </section>



            <!-- =================================================
                 PERFIL PRINCIPAL
            ================================================== -->

            <section class="profile-card">


                <!-- INFORMACIÓN PRINCIPAL -->

                <div class="profile-card__header">


                    <div class="profile-avatar">

                        <?= htmlspecialchars($inicial) ?>

                    </div>


                    <div class="profile-card__identity">


                        <h2>

                            <?= htmlspecialchars($nombre) ?>

                            <?= htmlspecialchars($apellido) ?>

                        </h2>


                        <span class="profile-role">

                            <?= htmlspecialchars($rol) ?>

                        </span>


                        <p>

                            <?= htmlspecialchars($correo) ?>

                        </p>


                    </div>


                    <button
                        type="button"
                        class="btn btn--secondary"
                        id="changePhotoButton"
                    >

                        Cambiar foto

                    </button>


                </div>


                <!-- =================================================
                     FORMULARIO
                ================================================== -->

                <form
                    action="#"
                    method="POST"
                    class="profile-form"
                >


                    <!-- INFORMACIÓN PERSONAL -->

                    <div class="profile-form__section">


                        <div class="profile-form__title">


                            <h2>
                                Información personal
                            </h2>


                            <p>
                                Actualiza los datos asociados a tu cuenta.
                            </p>


                        </div>


                        <div class="profile-form__grid">


                            <!-- NOMBRE -->

                            <div class="form-group">


                                <label for="nombre">
                                    Nombre
                                </label>


                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="<?= htmlspecialchars($nombre) ?>"
                                    placeholder="Tu nombre"
                                    required
                                >

                            </div>


                            <!-- APELLIDO -->

                            <div class="form-group">


                                <label for="apellido">
                                    Apellido
                                </label>


                                <input
                                    type="text"
                                    id="apellido"
                                    name="apellido"
                                    value="<?= htmlspecialchars($apellido) ?>"
                                    placeholder="Tu apellido"
                                    required
                                >

                            </div>


                            <!-- CORREO -->

                            <div class="form-group">


                                <label for="correo">
                                    Correo electrónico
                                </label>


                                <input
                                    type="email"
                                    id="correo"
                                    name="correo"
                                    value="<?= htmlspecialchars($correo) ?>"
                                    placeholder="correo@ejemplo.com"
                                    required
                                >

                            </div>


                            <!-- TELÉFONO -->

                            <div class="form-group">


                                <label for="telefono">
                                    Teléfono
                                </label>


                                <input
                                    type="tel"
                                    id="telefono"
                                    name="telefono"
                                    value="<?= htmlspecialchars($telefono) ?>"
                                    placeholder="Tu número telefónico"
                                >

                            </div>


                        </div>


                    </div>



                    <!-- =================================================
                         INFORMACIÓN DE CUENTA
                    ================================================== -->

                    <div class="profile-form__section">


                        <div class="profile-form__title">


                            <h2>
                                Información de cuenta
                            </h2>


                            <p>
                                Información relacionada con tu cuenta.
                            </p>


                        </div>


                        <div class="profile-account-info">


                            <div>

                                <span>
                                    Tipo de usuario
                                </span>

                                <strong>

                                    <?= htmlspecialchars($rol) ?>

                                </strong>

                            </div>


                            <div>

                                <span>
                                    Correo de acceso
                                </span>

                                <strong>

                                    <?= htmlspecialchars($correo) ?>

                                </strong>

                            </div>


                        </div>


                    </div>



                    <!-- =================================================
                         SEGURIDAD
                    ================================================== -->

                    <div class="profile-form__section">


                        <div class="profile-form__title">


                            <h2>
                                Seguridad
                            </h2>


                            <p>
                                Cambia tu contraseña cuando lo necesites.
                            </p>


                        </div>


                        <div class="profile-form__grid">


                            <!-- CONTRASEÑA ACTUAL -->

                            <div class="form-group">


                                <label for="password_actual">
                                    Contraseña actual
                                </label>


                                <input
                                    type="password"
                                    id="password_actual"
                                    name="password_actual"
                                    placeholder="••••••••"
                                >

                            </div>


                            <!-- NUEVA CONTRASEÑA -->

                            <div class="form-group">


                                <label for="password_nueva">
                                    Nueva contraseña
                                </label>


                                <input
                                    type="password"
                                    id="password_nueva"
                                    name="password_nueva"
                                    placeholder="••••••••"
                                >

                            </div>


                        </div>


                    </div>



                    <!-- =================================================
                         ACCIONES
                    ================================================== -->

                    <div class="profile-form__actions">


                        <button
                            type="reset"
                            class="btn btn--secondary"
                        >

                            Cancelar

                        </button>


                        <button
                            type="submit"
                            class="btn btn--primary"
                        >

                            Guardar cambios

                        </button>


                    </div>


                </form>


            </section>


        </div>


    </main>


</div>



<!-- JAVASCRIPT -->
<script src="<?= url('View/js/Main.js') ?>"></script>
</body>

</html>
