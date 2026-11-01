<?php

require_once __DIR__ . "/../../Middleware/AuthMiddleware.php";

AuthMiddleware::requiereRol([
    "Administrador",
    "Instructor",
    "Estudiante"
]);

$usuario = $datos["usuario"] ?? [];
$estadisticas = $datos["estadisticas"] ?? [];
$cursos = $datos["cursos"] ?? [];
$actividad = $datos["actividad"] ?? [];

$nombre = $usuario["nombre"] ?? "Usuario";
$apellido = $usuario["apellido"] ?? "";
$rol = $usuario["rol"] ?? "Usuario";

$inicial = mb_strtoupper(
    mb_substr($nombre, 0, 1, "UTF-8"),
    "UTF-8"
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
        Tablero | Academia
    </title>


    <!-- CSS GENERAL -->

    <link
        rel="stylesheet"
        href="../View/css/Style.css"
    >


    <!-- CSS DEL TABLERO -->

    <link
        rel="stylesheet"
        href="../View/css/Tablero.css"
    >

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
            <!-- BUSCADOR -->
            <div class="dashboard-header__search">
                <span>
                    🔍
                </span>
                <input
                    type="search"
                    placeholder="Buscar cursos..."
                >
            </div>
            <!-- ACCIONES -->
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

                <!-- USUARIO -->
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
             CONTENIDO
        ================================================== -->
        <div class="dashboard-content">
            <!-- =================================================
                 BIENVENIDA
            ================================================== -->
            <section class="dashboard-welcome">
                <div>
                    <span class="section-tag">
                        MI ESPACIO
                    </span>
                    <h1>
                        Buenos días,
                        <span>
                            <?= htmlspecialchars($nombre) ?>
                        </span>.
                    </h1>
                    <p>
                        Aquí tienes un resumen de tu actividad.
                    </p>
                </div>
                <?php if ($rol === "Estudiante"): ?>
                    <a
                        href="../Public/Busqueda.php"
                        class="btn btn--primary"
                    >
                        Explorar cursos
                    </a>
                <?php elseif ($rol === "Instructor"): ?>
                    <a
                        href="../Instructor/CreacionCurso.php"
                        class="btn btn--primary"
                    >
                        Crear curso
                    </a>
                <?php elseif ($rol === "Administrador"): ?>
                    <a
                        href="../Admin/ConsultaReportes.php"
                        class="btn btn--primary"
                    >
                        Consultar reportes
                    </a>
                <?php endif; ?>
            </section>

            <!-- =================================================
                 ESTADÍSTICAS
            ================================================== -->

            <section class="dashboard-stats">


                <?php foreach ($estadisticas as $estadistica): ?>


                    <div class="stat-card">


                        <div
                            class="
                                stat-card__icon
                                <?= htmlspecialchars(
                                    $estadistica["color"] ?? "purple"
                                ) ?>
                            "
                        >

                            <?= htmlspecialchars(
                                $estadistica["icono"] ?? "•"
                            ) ?>

                        </div>


                        <div>

                            <span>

                                <?= htmlspecialchars(
                                    $estadistica["nombre"]
                                ) ?>

                            </span>


                            <strong>

                                <?= htmlspecialchars(
                                    $estadistica["valor"]
                                ) ?>

                            </strong>

                        </div>


                    </div>


                <?php endforeach; ?>


            </section>



            <!-- =================================================
                 CONTENIDO SEGÚN EL ROL
            ================================================== -->

            <?php if ($rol === "Estudiante"): ?>


                <!-- =============================================
                     ESTUDIANTE
                ============================================== -->

                <section class="dashboard-section">


                    <div class="dashboard-section__header">


                        <div>

                            <h2>
                                Continúa aprendiendo
                            </h2>

                            <p>
                                Retoma tus cursos donde los dejaste.
                            </p>

                        </div>


                        <a href="../Estudiante/Cursos.php">
                            Ver todos
                        </a>


                    </div>


                    <div class="student-courses">


                        <?php foreach ($cursos as $curso): ?>


                            <article class="student-course">


                                <div
                                    class="
                                        student-course__image
                                        <?= htmlspecialchars(
                                            $curso["color"] ?? "purple"
                                        ) ?>
                                    "
                                >

                                    <span>

                                        <?= htmlspecialchars(
                                            $curso["categoria"]
                                        ) ?>

                                    </span>

                                </div>


                                <div
                                    class="
                                        student-course__content
                                    "
                                >


                                    <div>

                                        <span
                                            class="course-category"
                                        >

                                            <?= htmlspecialchars(
                                                $curso["categoria"]
                                            ) ?>

                                        </span>


                                        <h3>

                                            <?= htmlspecialchars(
                                                $curso["titulo"]
                                            ) ?>

                                        </h3>


                                        <p>

                                            Instructor:

                                            <?= htmlspecialchars(
                                                $curso["instructor"]
                                            ) ?>

                                        </p>

                                    </div>


                                    <div
                                        class="course-progress"
                                    >


                                        <div
                                            class="
                                                course-progress__header
                                            "
                                        >

                                            <span>
                                                Progreso
                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $curso["progreso"]
                                                ) ?>%

                                            </strong>

                                        </div>


                                        <div class="progress-bar">

                                            <span
                                                style="
                                                    width:
                                                    <?= htmlspecialchars(
                                                        $curso["progreso"]
                                                    ) ?>%;
                                                "
                                            ></span>

                                        </div>


                                    </div>


                                </div>


                                <div
                                    class="
                                        student-course__action
                                    "
                                >

                                    <a
                                        href="../Estudiante/Curso.php?id=<?= urlencode($curso["id"]) ?>"
                                        class="btn btn--primary"
                                    >
                                        Continuar
                                    </a>

                                </div>


                            </article>


                        <?php endforeach; ?>


                    </div>


                </section>



            <?php elseif ($rol === "Instructor"): ?>


                <!-- =============================================
                     INSTRUCTOR
                ============================================== -->

                <section class="dashboard-section">


                    <div class="dashboard-section__header">


                        <div>

                            <h2>
                                Mis cursos
                            </h2>

                            <p>
                                Administra los cursos que has creado.
                            </p>

                        </div>


                        <a
                            href="../Instructor/MisCursos.php"
                        >
                            Ver todos
                        </a>


                    </div>


                    <div class="student-courses">


                        <?php foreach ($cursos as $curso): ?>


                            <article class="student-course">


                                <div
                                    class="
                                        student-course__image
                                        <?= htmlspecialchars(
                                            $curso["color"] ?? "purple"
                                        ) ?>
                                    "
                                >

                                    <span>
                                        <?= htmlspecialchars(
                                            $curso["categoria"]
                                        ) ?>
                                    </span>

                                </div>


                                <div
                                    class="
                                        student-course__content
                                    "
                                >


                                    <span
                                        class="course-category"
                                    >

                                        <?= htmlspecialchars(
                                            $curso["categoria"]
                                        ) ?>

                                    </span>


                                    <h3>

                                        <?= htmlspecialchars(
                                            $curso["titulo"]
                                        ) ?>

                                    </h3>


                                    <p>

                                        <?= htmlspecialchars(
                                            $curso["estudiantes"] ?? 0
                                        ) ?>

                                        estudiantes inscritos

                                    </p>


                                </div>


                                <div
                                    class="
                                        student-course__action
                                    "
                                >

                                    <a
                                        href="../Instructor/MisCursos.php?id=<?= urlencode($curso["id"]) ?>"
                                        class="btn btn--primary"
                                    >
                                        Administrar
                                    </a>

                                </div>


                            </article>


                        <?php endforeach; ?>


                    </div>


                </section>



            <?php elseif ($rol === "Administrador"): ?>


                <!-- =============================================
                     ADMINISTRADOR
                ============================================== -->

                <section class="dashboard-section">


                    <div class="dashboard-section__header">


                        <div>

                            <h2>
                                Actividad del sistema
                            </h2>

                            <p>
                                Resumen general de la plataforma.
                            </p>

                        </div>


                        <a
                            href="../Admin/ConsultaReportes.php"
                        >
                            Ver reportes
                        </a>


                    </div>


                    <div class="student-courses">


                        <?php foreach ($actividad as $item): ?>


                            <article
                                class="dashboard-panel"
                            >


                                <div>

                                    <span
                                        class="course-category"
                                    >

                                        <?= htmlspecialchars(
                                            $item["tipo"]
                                        ) ?>

                                    </span>


                                    <h3>

                                        <?= htmlspecialchars(
                                            $item["titulo"]
                                        ) ?>

                                    </h3>


                                    <p>

                                        <?= htmlspecialchars(
                                            $item["descripcion"]
                                        ) ?>

                                    </p>

                                </div>


                            </article>


                        <?php endforeach; ?>


                    </div>


                </section>


            <?php endif; ?>



            <!-- =================================================
                 ACTIVIDAD RECIENTE
            ================================================== -->

            <?php if (!empty($actividad)): ?>


                <div class="dashboard-bottom">


                    <section class="dashboard-panel">


                        <div class="dashboard-panel__header">


                            <div>

                                <h2>
                                    Actividad reciente
                                </h2>

                                <p>
                                    Últimos movimientos.
                                </p>

                            </div>


                        </div>


                        <div class="activity-list">


                            <?php foreach ($actividad as $item): ?>


                                <div class="activity">


                                    <div
                                        class="
                                            activity__icon
                                            <?= htmlspecialchars(
                                                $item["color"] ?? "purple"
                                            ) ?>
                                        "
                                    >

                                        <?= htmlspecialchars(
                                            $item["icono"] ?? "•"
                                        ) ?>

                                    </div>


                                    <div>


                                        <strong>

                                            <?= htmlspecialchars(
                                                $item["titulo"]
                                            ) ?>

                                        </strong>


                                        <p>

                                            <?= htmlspecialchars(
                                                $item["descripcion"]
                                            ) ?>

                                        </p>


                                        <small>

                                            <?= htmlspecialchars(
                                                $item["fecha"] ?? ""
                                            ) ?>

                                        </small>


                                    </div>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    </section>


                </div>


            <?php endif; ?>


        </div>


    </main>


</div>



<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script src="../View/js/Main.js"></script>


</body>

</html>
