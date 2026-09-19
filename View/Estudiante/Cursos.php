<?php

/*
|--------------------------------------------------------------------------
| CURSOS DEL ESTUDIANTE
|--------------------------------------------------------------------------
|
| Esta vista recibe la información desde el Controller.
|
| Datos esperados:
|
| $datos["usuario"]
| $datos["cursos"]
|
|--------------------------------------------------------------------------
*/


$usuario = $datos["usuario"] ?? [];

$cursos = $datos["cursos"] ?? [];


// =====================================================
// DATOS DEL USUARIO
// =====================================================

$nombre = $usuario["nombre"] ?? "Estudiante";

$apellido = $usuario["apellido"] ?? "";

$rol = $usuario["rol"] ?? "Estudiante";


$inicial = strtoupper(
    substr($nombre, 0, 1)
);


// =====================================================
// DATOS DE EJEMPLO
// =====================================================

if (empty($cursos)) {

    $cursos = [

        [
            "id" => 1,
            "titulo" => "PHP desde cero",
            "categoria" => "Programación",
            "instructor" => "Carlos Ramírez",
            "progreso" => 72,
            "lecciones" => 32,
            "completadas" => 23,
            "duracion" => "12 h 30 min",
            "estado" => "En progreso",
            "color" => "purple"
        ],

        [
            "id" => 2,
            "titulo" => "JavaScript moderno",
            "categoria" => "Programación",
            "instructor" => "Laura Martínez",
            "progreso" => 45,
            "lecciones" => 40,
            "completadas" => 18,
            "duracion" => "16 h 20 min",
            "estado" => "En progreso",
            "color" => "yellow"
        ],

        [
            "id" => 3,
            "titulo" => "Diseño de interfaces UI/UX",
            "categoria" => "Diseño",
            "instructor" => "Sofía Hernández",
            "progreso" => 88,
            "lecciones" => 25,
            "completadas" => 22,
            "duracion" => "9 h 45 min",
            "estado" => "En progreso",
            "color" => "pink"
        ],

        [
            "id" => 4,
            "titulo" => "Bases de datos con MySQL",
            "categoria" => "Bases de datos",
            "instructor" => "Miguel Torres",
            "progreso" => 100,
            "lecciones" => 28,
            "completadas" => 28,
            "duracion" => "10 h 15 min",
            "estado" => "Completado",
            "color" => "blue"
        ],

        [
            "id" => 5,
            "titulo" => "HTML y CSS profesional",
            "categoria" => "Desarrollo web",
            "instructor" => "Daniel García",
            "progreso" => 20,
            "lecciones" => 35,
            "completadas" => 7,
            "duracion" => "14 h 10 min",
            "estado" => "En progreso",
            "color" => "green"
        ],

        [
            "id" => 6,
            "titulo" => "Introducción a Python",
            "categoria" => "Programación",
            "instructor" => "Ana López",
            "progreso" => 100,
            "lecciones" => 30,
            "completadas" => 30,
            "duracion" => "11 h 40 min",
            "estado" => "Completado",
            "color" => "orange"
        ]

    ];

}


// =====================================================
// ESTADÍSTICAS
// =====================================================

$totalCursos = count($cursos);

$cursosCompletados = 0;

$totalProgreso = 0;


foreach ($cursos as $curso) {

    $progreso = (int) ($curso["progreso"] ?? 0);

    $totalProgreso += $progreso;


    if ($progreso >= 100) {

        $cursosCompletados++;

    }

}


$progresoGeneral = $totalCursos > 0
    ? round($totalProgreso / $totalCursos)
    : 0;


$cursosEnProgreso =
    $totalCursos - $cursosCompletados;

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
        Mis cursos | Academia
    </title>


    <!-- CSS GENERAL -->

    <link
        rel="stylesheet"
        href="../css/Style.css"
    >


    <!-- CSS DEL TABLERO -->

    <link
        rel="stylesheet"
        href="../css/Tablero.css"
    >


    <!-- CSS DE CURSOS -->

    <link
        rel="stylesheet"
        href="../css/Cursos.css"
    >

</head>


<body class="dashboard-body">


<div class="dashboard">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">


        <div class="sidebar__brand">

            <a
                href="../Public/Index.php"
                class="sidebar__logo"
            >
                ACADEMIA
            </a>

        </div>


        <!-- PERFIL -->

        <div class="sidebar__profile">


            <div class="sidebar__avatar">

                <?= htmlspecialchars($inicial) ?>

            </div>


            <div>

                <strong>

                    <?= htmlspecialchars($nombre) ?>

                    <?= htmlspecialchars($apellido) ?>

                </strong>


                <span>

                    <?= htmlspecialchars($rol) ?>

                </span>

            </div>


        </div>


        <!-- NAVEGACIÓN -->

        <nav class="sidebar__nav">


            <span class="sidebar__label">
                PRINCIPAL
            </span>


            <a
                href="../Compartidas/Tablero.php"
                class="sidebar__link"
            >

                <span>▦</span>

                Tablero

            </a>


            <a
                href="Cursos.php"
                class="sidebar__link active"
            >

                <span>▣</span>

                Mis cursos

            </a>


            <a
                href="Kardex.php"
                class="sidebar__link"
            >

                <span>▤</span>

                Kardex

            </a>


            <a
                href="../Compartidas/ChatCurso.php"
                class="sidebar__link"
            >

                <span>◌</span>

                Chat

            </a>


            <span class="sidebar__label">
                CUENTA
            </span>


            <a
                href="../Compartidas/Perfil.php"
                class="sidebar__link"
            >

                <span>⚙</span>

                Mi perfil

            </a>


        </nav>


        <!-- CERRAR SESIÓN -->

        <div class="sidebar__bottom">

            <a
                href="../Public/Index.php"
                class="sidebar__logout"
            >

                <span>←</span>

                Cerrar sesión

            </a>

        </div>


    </aside>



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
             CONTENIDO
        ================================================== -->

        <div class="dashboard-content cursos-page">


            <!-- =================================================
                 CABECERA
            ================================================== -->

            <section class="courses-heading">


                <div>

                    <span class="section-tag">
                        APRENDIZAJE
                    </span>


                    <h1>
                        Mis cursos
                    </h1>


                    <p>
                        Continúa aprendiendo y alcanza tus objetivos.
                    </p>

                </div>


                <a
                    href="../Public/Busqueda.php"
                    class="btn btn--primary"
                >

                    Explorar cursos

                </a>


            </section>



            <!-- =================================================
                 RESUMEN
            ================================================== -->

            <section class="courses-summary">


                <div class="summary-card">


                    <div class="summary-card__icon purple">
                        ▣
                    </div>


                    <div>

                        <span>
                            Cursos inscritos
                        </span>


                        <strong>
                            <?= $totalCursos ?>
                        </strong>

                    </div>


                </div>


                <div class="summary-card">


                    <div class="summary-card__icon blue">
                        ◔
                    </div>


                    <div>

                        <span>
                            En progreso
                        </span>


                        <strong>
                            <?= $cursosEnProgreso ?>
                        </strong>

                    </div>


                </div>


                <div class="summary-card">


                    <div class="summary-card__icon green">
                        ✓
                    </div>


                    <div>

                        <span>
                            Completados
                        </span>


                        <strong>
                            <?= $cursosCompletados ?>
                        </strong>

                    </div>


                </div>


                <div class="summary-card">


                    <div class="summary-card__icon orange">
                        %
                    </div>


                    <div>

                        <span>
                            Progreso general
                        </span>


                        <strong>
                            <?= $progresoGeneral ?>%
                        </strong>

                    </div>


                </div>


            </section>



            <!-- =================================================
                 FILTROS
            ================================================== -->

            <section class="courses-toolbar">


                <div class="courses-toolbar__title">

                    <h2>
                        Mis cursos
                    </h2>


                    <span>
                        <?= $totalCursos ?> cursos
                    </span>

                </div>


                <div class="courses-toolbar__actions">


                    <div class="course-search">

                        <span>
                            🔍
                        </span>


                        <input
                            type="search"
                            placeholder="Buscar en mis cursos..."
                        >

                    </div>


                    <select
                        class="course-filter"
                        aria-label="Filtrar cursos"
                    >

                        <option value="todos">
                            Todos
                        </option>

                        <option value="progreso">
                            En progreso
                        </option>

                        <option value="completados">
                            Completados
                        </option>

                    </select>


                </div>


            </section>



            <!-- =================================================
                 GRID DE CURSOS
            ================================================== -->

            <section class="courses-grid">


                <?php foreach ($cursos as $curso): ?>


                    <?php

                    $progreso =
                        (int) ($curso["progreso"] ?? 0);

                    $completado =
                        $progreso >= 100;

                    ?>


                    <article class="course-card">


                        <!-- IMAGEN -->

                        <div
                            class="
                                course-card__image
                                <?= htmlspecialchars(
                                    $curso["color"] ?? "purple"
                                ) ?>
                            "
                        >


                            <span class="course-card__category">

                                <?= htmlspecialchars(
                                    $curso["categoria"]
                                ) ?>

                            </span>


                            <div class="course-card__image-icon">

                                <?php if ($completado): ?>

                                    ✓

                                <?php else: ?>

                                    ▶

                                <?php endif; ?>

                            </div>


                        </div>



                        <!-- CONTENIDO -->

                        <div class="course-card__content">


                            <div class="course-card__top">


                                <span
                                    class="
                                        course-status
                                        <?= $completado
                                            ? "completed"
                                            : "" ?>
                                    "
                                >

                                    <?php if ($completado): ?>

                                        ✓ Completado

                                    <?php else: ?>

                                        En progreso

                                    <?php endif; ?>

                                </span>


                                <span class="course-duration">

                                    <?= htmlspecialchars(
                                        $curso["duracion"]
                                    ) ?>

                                </span>


                            </div>


                            <h3>

                                <?= htmlspecialchars(
                                    $curso["titulo"]
                                ) ?>

                            </h3>


                            <p class="course-instructor">

                                Instructor:

                                <strong>

                                    <?= htmlspecialchars(
                                        $curso["instructor"]
                                    ) ?>

                                </strong>

                            </p>



                            <!-- PROGRESO -->

                            <div class="course-card__progress">


                                <div
                                    class="
                                        course-progress__header
                                    "
                                >

                                    <span>
                                        Progreso
                                    </span>


                                    <strong>

                                        <?= $progreso ?>%

                                    </strong>

                                </div>


                                <div class="progress-bar">

                                    <span
                                        style="
                                            width:
                                            <?= $progreso ?>%;
                                        "
                                    ></span>

                                </div>


                                <small>

                                    <?= htmlspecialchars(
                                        $curso["completadas"] ?? 0
                                    ) ?>

                                    de

                                    <?= htmlspecialchars(
                                        $curso["lecciones"] ?? 0
                                    ) ?>

                                    lecciones completadas

                                </small>


                            </div>



                            <!-- ACCIÓN -->

                            <a
                                href="../Public/CursoPreambulo.php?id=<?= urlencode($curso["id"]) ?>"
                                class="
                                    btn
                                    <?= $completado
                                        ? "btn--outline"
                                        : "btn--primary" ?>
                                    course-card__button
                                "
                            >

                                <?php if ($completado): ?>

                                    Ver curso

                                <?php else: ?>

                                    Continuar aprendiendo

                                <?php endif; ?>


                            </a>


                        </div>


                    </article>


                <?php endforeach; ?>


            </section>



            <!-- =================================================
                 SIN CURSOS
            ================================================== -->

            <?php if (empty($cursos)): ?>


                <section class="courses-empty">


                    <div class="courses-empty__icon">
                        ▣
                    </div>


                    <h2>
                        Todavía no tienes cursos
                    </h2>


                    <p>
                        Explora nuestro catálogo y comienza
                        tu aprendizaje.
                    </p>


                    <a
                        href="../Public/Busqueda.php"
                        class="btn btn--primary"
                    >
                        Explorar cursos
                    </a>


                </section>


            <?php endif; ?>


        </div>


    </main>


</div>



<script src="../js/Main.js"></script>


</body>

</html>
