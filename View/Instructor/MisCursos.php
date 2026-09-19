<?php

/*
|--------------------------------------------------------------------------
| MIS CURSOS - INSTRUCTOR
|--------------------------------------------------------------------------
|
| Por ahora se utilizan datos de ejemplo únicamente para visualizar
| el diseño de la interfaz.
|
| Posteriormente estos datos serán proporcionados por el Controller.
|
|--------------------------------------------------------------------------
*/


$nombre = "Carlos";
$apellido = "Ramírez";
$rol = "Instructor";

$inicial = strtoupper(
    substr($nombre, 0, 1)
);


// Cursos de ejemplo

$cursos = [

    [
        "id" => 1,
        "titulo" => "PHP desde cero",
        "categoria" => "Programación",
        "estudiantes" => 125,
        "precio" => "499",
        "estado" => "Publicado",
        "color" => "purple"
    ],

    [
        "id" => 2,
        "titulo" => "JavaScript moderno",
        "categoria" => "Programación",
        "estudiantes" => 84,
        "precio" => "599",
        "estado" => "Publicado",
        "color" => "blue"
    ],

    [
        "id" => 3,
        "titulo" => "Diseño de interfaces UI/UX",
        "categoria" => "Diseño",
        "estudiantes" => 42,
        "precio" => "449",
        "estado" => "Borrador",
        "color" => "orange"
    ],

    [
        "id" => 4,
        "titulo" => "Bases de datos con MySQL",
        "categoria" => "Bases de datos",
        "estudiantes" => 67,
        "precio" => "399",
        "estado" => "Publicado",
        "color" => "green"
    ]

];

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


    <!-- CSS DEL DASHBOARD -->

    <link
        rel="stylesheet"
        href="../css/Tablero.css"
    >


    <!-- CSS DE MIS CURSOS -->

    <link
        rel="stylesheet"
        href="../css/MisCursos.css"
    >

</head>


<body class="dashboard-body">


<div class="dashboard">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">


        <!-- LOGO -->

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
                href="MisCursos.php"
                class="sidebar__link active"
            >

                <span>▣</span>

                Mis cursos

            </a>


            <a
                href="CreacionCurso.php"
                class="sidebar__link"
            >

                <span>＋</span>

                Crear curso

            </a>


            <a
                href="Ventas.php"
                class="sidebar__link"
            >

                <span>$</span>

                Ventas

            </a>


            <a
                href="ChatCurso.php"
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


        <!-- HEADER -->

        <header class="dashboard-header">


            <button
                type="button"
                class="mobile-menu"
                id="mobileMenu"
            >
                ☰
            </button>


            <div class="dashboard-header__search">

                <span>🔍</span>

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

                    <span>2</span>

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



        <!-- =====================================================
             CONTENIDO
        ====================================================== -->

        <div class="dashboard-content mis-cursos">


            <!-- ENCABEZADO -->

            <section class="mis-cursos__header">


                <div>

                    <span class="section-tag">
                        INSTRUCTOR
                    </span>


                    <h1>
                        Mis cursos
                    </h1>


                    <p>
                        Administra y supervisa los cursos que has creado.
                    </p>

                </div>


                <a
                    href="CreacionCurso.php"
                    class="btn btn--primary"
                >

                    ＋ Crear curso

                </a>


            </section>



            <!-- =================================================
                 RESUMEN
            ================================================== -->

            <section class="course-summary">


                <div class="course-summary__card">


                    <div class="course-summary__icon purple">
                        ▣
                    </div>


                    <div>

                        <span>
                            Cursos totales
                        </span>


                        <strong>
                            4
                        </strong>

                    </div>


                </div>


                <div class="course-summary__card">


                    <div class="course-summary__icon green">
                        ✓
                    </div>


                    <div>

                        <span>
                            Publicados
                        </span>


                        <strong>
                            3
                        </strong>

                    </div>


                </div>


                <div class="course-summary__card">


                    <div class="course-summary__icon orange">
                        ◷
                    </div>


                    <div>

                        <span>
                            Borradores
                        </span>


                        <strong>
                            1
                        </strong>

                    </div>


                </div>


                <div class="course-summary__card">


                    <div class="course-summary__icon blue">
                        ◎
                    </div>


                    <div>

                        <span>
                            Estudiantes
                        </span>


                        <strong>
                            318
                        </strong>

                    </div>


                </div>


            </section>



            <!-- =================================================
                 FILTROS
            ================================================== -->

            <section class="course-filters">


                <div class="course-filters__tabs">

                    <button
                        type="button"
                        class="course-filter active"
                    >
                        Todos
                    </button>


                    <button
                        type="button"
                        class="course-filter"
                    >
                        Publicados
                    </button>


                    <button
                        type="button"
                        class="course-filter"
                    >
                        Borradores
                    </button>

                </div>


                <div class="course-filters__search">

                    <span>⌕</span>

                    <input
                        type="search"
                        placeholder="Buscar entre mis cursos..."
                    >

                </div>


            </section>



            <!-- =================================================
                 LISTA DE CURSOS
            ================================================== -->

            <section class="instructor-courses">


                <?php foreach ($cursos as $curso): ?>


                    <article class="instructor-course">


                        <!-- IMAGEN -->

                        <div
                            class="
                                instructor-course__image
                                <?= htmlspecialchars(
                                    $curso["color"]
                                ) ?>
                            "
                        >

                            <span>
                                <?= htmlspecialchars(
                                    $curso["categoria"]
                                ) ?>
                            </span>


                            <div class="course-image-placeholder">
                                ACADEMIA
                            </div>

                        </div>



                        <!-- INFORMACIÓN -->

                        <div class="instructor-course__content">


                            <div
                                class="
                                    instructor-course__category
                                "
                            >

                                <?= htmlspecialchars(
                                    $curso["categoria"]
                                ) ?>

                            </div>


                            <h2>

                                <?= htmlspecialchars(
                                    $curso["titulo"]
                                ) ?>

                            </h2>


                            <div
                                class="
                                    instructor-course__meta
                                "
                            >

                                <span>
                                    👥
                                    <?= htmlspecialchars(
                                        $curso["estudiantes"]
                                    ) ?>
                                    estudiantes
                                </span>


                                <span>
                                    $
                                    <?= htmlspecialchars(
                                        $curso["precio"]
                                    ) ?>
                                </span>

                            </div>


                            <div
                                class="
                                    instructor-course__status
                                    <?= strtolower(
                                        $curso["estado"]
                                    ) === "publicado"
                                        ? "published"
                                        : "draft"
                                    ?>
                                "
                            >

                                <span></span>

                                <?= htmlspecialchars(
                                    $curso["estado"]
                                ) ?>

                            </div>


                        </div>



                        <!-- ACCIONES -->

                        <div
                            class="
                                instructor-course__actions
                            "
                        >


                            <?php if (
                                $curso["estado"] === "Borrador"
                            ): ?>


                                <a
                                    href="CreacionCurso.php?id=<?= urlencode($curso["id"]) ?>"
                                    class="btn btn--primary"
                                >
                                    Continuar edición
                                </a>


                            <?php else: ?>


                                <a
                                    href="CreacionCurso.php?id=<?= urlencode($curso["id"]) ?>"
                                    class="btn btn--outline"
                                >
                                    Editar
                                </a>


                                <a
                                    href="../Public/CursoPreambulo.php?id=<?= urlencode($curso["id"]) ?>"
                                    class="btn btn--secondary"
                                >
                                    Ver curso
                                </a>


                            <?php endif; ?>


                            <button
                                type="button"
                                class="course-more"
                                aria-label="Más opciones"
                            >
                                ⋮
                            </button>


                        </div>


                    </article>


                <?php endforeach; ?>


            </section>


        </div>


    </main>


</div>



<script src="../js/Main.js"></script>


</body>

</html>