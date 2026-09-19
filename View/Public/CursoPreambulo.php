<?php

    /*
     * =========================================
     * ID DEL CURSO
     * =========================================
     *
     * Posteriormente el Controller se encargará
     * de obtener el curso correspondiente.
     */

    $cursoId = isset($_GET["id"])
        ? (int) $_GET["id"]
        : 1;


    /*
     * =========================================
     * DATOS TEMPORALES DEL CURSO
     * =========================================
     */

    $curso = [

        "id" => $cursoId,

        "titulo" => "JavaScript desde cero",

        "descripcion_corta" =>
            "Aprende JavaScript desde las bases hasta la creación de aplicaciones web interactivas.",

        "descripcion" =>
            "Este curso está diseñado para personas que quieren comenzar a programar con JavaScript y desarrollar una base sólida para continuar con el desarrollo web.",

        "categoria" => "Programación",

        "instructor" => "Juan Pérez",

        "instructor_descripcion" =>
            "Desarrollador web e instructor especializado en tecnologías frontend y backend.",

        "rating" => "4.8",

        "total_resenas" => 86,

        "estudiantes" => 325,

        "nivel" => "Principiante",

        "duracion" => "12 horas",

        "lecciones" => 35,

        "idioma" => "Español",

        "precio" => 299,

        "precio_anterior" => 499,

        "certificado" => true,

        "actualizado" => "Agosto 2026"

    ];


    /*
     * =========================================
     * LO QUE APRENDERÁS
     * =========================================
     */

    $aprendizajes = [

        "Comprender los fundamentos de JavaScript",

        "Trabajar con variables, funciones y objetos",

        "Manipular elementos del DOM",

        "Crear formularios interactivos",

        "Consumir información mediante APIs",

        "Desarrollar pequeños proyectos web"

    ];


    /*
     * =========================================
     * CONTENIDO DEL CURSO
     * =========================================
     */

    $secciones = [

        [
            "titulo" => "Introducción a JavaScript",
            "lecciones" => 5,
            "duracion" => "45 min"
        ],

        [
            "titulo" => "Variables y tipos de datos",
            "lecciones" => 6,
            "duracion" => "1 h 20 min"
        ],

        [
            "titulo" => "Funciones y estructuras",
            "lecciones" => 7,
            "duracion" => "2 h"
        ],

        [
            "titulo" => "DOM y eventos",
            "lecciones" => 8,
            "duracion" => "3 h"
        ],

        [
            "titulo" => "APIs y proyectos",
            "lecciones" => 9,
            "duracion" => "4 h 55 min"
        ]

    ];


    /*
     * =========================================
     * COMENTARIOS
     * =========================================
     */

    $resenas = [

        [
            "usuario" => "Daniel Torres",
            "rating" => 5,
            "comentario" =>
                "Muy buen curso para comenzar. Las explicaciones son claras y los ejercicios ayudan bastante."
        ],

        [
            "usuario" => "Laura Sánchez",
            "rating" => 5,
            "comentario" =>
                "Me gustó mucho la forma en que se explica cada tema. Lo recomiendo para principiantes."
        ],

        [
            "usuario" => "Miguel Rodríguez",
            "rating" => 4,
            "comentario" =>
                "Buen contenido y ejemplos prácticos. Me hubiera gustado que tuviera más proyectos."
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
        <?= htmlspecialchars($curso["titulo"]) ?>
        | Academia
    </title>


    <!-- CSS GENERAL -->

    <link
        rel="stylesheet"
        href="../css/Style.css"
    >


    <!-- CSS DEL CURSO -->

    <link
        rel="stylesheet"
        href="../css/Curso_preambulo.css"
    >

</head>


<body>


    <!-- =====================================
         NAVBAR
    ====================================== -->
    <?php include("../Compartidas/Header.php")?>


    <!-- =====================================
         INFORMACIÓN PRINCIPAL
    ====================================== -->

    <main>


        <section class="course-hero">

            <div class="container">


                <!-- MIGAS -->

                <div class="breadcrumbs">

                    <a href="../Public/Index.php">
                        Inicio
                    </a>

                    <span>
                        /
                    </span>

                    <a href="../Public/Categorias.php">
                        <?= htmlspecialchars(
                            $curso["categoria"]
                        ) ?>
                    </a>

                    <span>
                        /
                    </span>

                    <span>
                        Curso
                    </span>

                </div>


                <div class="course-hero__grid">


                    <!-- =================================
                         INFORMACIÓN
                    ================================== -->

                    <div class="course-hero__info">


                        <span class="course-category">

                            <?= htmlspecialchars(
                                $curso["categoria"]
                            ) ?>

                        </span>


                        <h1>

                            <?= htmlspecialchars(
                                $curso["titulo"]
                            ) ?>

                        </h1>


                        <p class="course-short-description">

                            <?= htmlspecialchars(
                                $curso["descripcion_corta"]
                            ) ?>

                        </p>


                        <!-- RATING -->

                        <div class="course-rating">

                            <strong>
                                <?= $curso["rating"] ?>
                            </strong>

                            <span class="stars">
                                ★★★★★
                            </span>

                            <a href="#resenas">

                                <?= $curso["total_resenas"] ?>
                                reseñas

                            </a>

                            <span>
                                ·
                            </span>

                            <span>
                                <?= $curso["estudiantes"] ?>
                                estudiantes
                            </span>

                        </div>


                        <!-- INSTRUCTOR -->

                        <p class="course-instructor">

                            Creado por

                            <strong>
                                <?= htmlspecialchars(
                                    $curso["instructor"]
                                ) ?>
                            </strong>

                        </p>


                        <!-- INFORMACIÓN -->

                        <div class="course-meta">

                            <span>
                                ◷
                                <?= htmlspecialchars(
                                    $curso["duracion"]
                                ) ?>
                            </span>

                            <span>
                                ▣
                                <?= $curso["lecciones"] ?>
                                lecciones
                            </span>

                            <span>
                                ◉
                                <?= htmlspecialchars(
                                    $curso["nivel"]
                                ) ?>
                            </span>

                            <span>
                                A
                                <?= htmlspecialchars(
                                    $curso["idioma"]
                                ) ?>
                            </span>

                        </div>


                    </div>


                    <!-- =================================
                         TARJETA DE COMPRA
                    ================================== -->

                    <aside class="enrollment-card">


                        <!-- IMAGEN / VIDEO -->

                        <div class="course-preview">

                            <div class="course-preview__play">

                                ▶

                            </div>

                            <span>
                                Vista previa del curso
                            </span>

                        </div>


                        <div class="enrollment-card__body">


                            <!-- PRECIO -->

                            <div class="course-price">

                                <strong>
                                    $<?= $curso["precio"] ?>
                                </strong>

                                <del>
                                    $<?= $curso["precio_anterior"] ?>
                                </del>

                            </div>


                            <p class="price-note">
                                Acceso completo al curso
                            </p>


                            <!-- BOTÓN -->

                            <a
                                href="../Auth/Login.php?curso=<?= $curso["id"] ?>"
                                class="btn btn--primary btn--large"
                            >
                                Inscribirme ahora
                            </a>


                            <p class="guarantee">
                                Acceso inmediato después
                                de la inscripción.
                            </p>


                            <!-- INCLUYE -->

                            <div class="includes">

                                <h3>
                                    Este curso incluye:
                                </h3>


                                <ul>

                                    <li>
                                        ✓
                                        <?= $curso["duracion"] ?>
                                        de contenido
                                    </li>

                                    <li>
                                        ✓
                                        <?= $curso["lecciones"] ?>
                                        lecciones
                                    </li>

                                    <li>
                                        ✓
                                        Acceso desde cualquier dispositivo
                                    </li>

                                    <?php if ($curso["certificado"]): ?>

                                        <li>
                                            ✓
                                            Certificado de finalización
                                        </li>

                                    <?php endif; ?>

                                </ul>

                            </div>


                        </div>


                    </aside>


                </div>


            </div>

        </section>


        <!-- =====================================
             CONTENIDO DEL CURSO
        ====================================== -->

        <section class="course-content">

            <div class="container course-content__layout">


                <!-- =================================
                     CONTENIDO PRINCIPAL
                ================================== -->

                <div class="course-main">


                    <!-- DESCRIPCIÓN -->

                    <section class="course-section">

                        <h2>
                            Sobre este curso
                        </h2>

                        <p>
                            <?= htmlspecialchars(
                                $curso["descripcion"]
                            ) ?>
                        </p>

                    </section>


                    <!-- APRENDIZAJES -->

                    <section class="course-section">

                        <h2>
                            Lo que aprenderás
                        </h2>


                        <div class="learning-grid">

                            <?php foreach ($aprendizajes as $aprendizaje): ?>

                                <div class="learning-item">

                                    <span>
                                        ✓
                                    </span>

                                    <p>
                                        <?= htmlspecialchars(
                                            $aprendizaje
                                        ) ?>
                                    </p>

                                </div>

                            <?php endforeach; ?>

                        </div>


                    </section>


                    <!-- CONTENIDO -->

                    <section class="course-section">

                        <div class="section-title-row">

                            <h2>
                                Contenido del curso
                            </h2>

                            <span>
                                <?= $curso["lecciones"] ?>
                                lecciones
                            </span>

                        </div>


                        <div class="course-sections">


                            <?php foreach ($secciones as $indice => $seccion): ?>

                                <details
                                    class="course-module"
                                    <?= $indice === 0
                                        ? "open"
                                        : "" ?>
                                >


                                    <summary>


                                        <div>

                                            <strong>

                                                <?= $indice + 1 ?>.

                                                <?= htmlspecialchars(
                                                    $seccion["titulo"]
                                                ) ?>

                                            </strong>

                                            <span>

                                                <?= $seccion["lecciones"] ?>
                                                lecciones ·
                                                <?= $seccion["duracion"] ?>

                                            </span>

                                        </div>


                                        <span class="module-arrow">
                                            +
                                        </span>


                                    </summary>


                                    <div class="module-content">

                                        <div>
                                            Lección de introducción
                                        </div>

                                        <div>
                                            Conceptos fundamentales
                                        </div>

                                        <div>
                                            Ejercicios prácticos
                                        </div>

                                    </div>


                                </details>

                            <?php endforeach; ?>


                        </div>

                    </section>


                    <!-- REQUISITOS -->

                    <section class="course-section">

                        <h2>
                            Requisitos
                        </h2>

                        <ul class="requirements">

                            <li>
                                No necesitas experiencia previa.
                            </li>

                            <li>
                                Tener una computadora con conexión a internet.
                            </li>

                            <li>
                                Tener interés por aprender programación.
                            </li>

                        </ul>

                    </section>


                    <!-- INSTRUCTOR -->

                    <section class="course-section instructor-section">


                        <h2>
                            Instructor
                        </h2>


                        <div class="instructor">


                            <div class="instructor__avatar">
                                JP
                            </div>


                            <div>

                                <h3>
                                    <?= htmlspecialchars(
                                        $curso["instructor"]
                                    ) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars(
                                        $curso["instructor_descripcion"]
                                    ) ?>
                                </p>

                            </div>


                        </div>


                    </section>


                    <!-- =================================
                         RESEÑAS
                    ================================== -->

                    <section
                        class="course-section"
                        id="resenas"
                    >


                        <h2>
                            Opiniones de estudiantes
                        </h2>


                        <!-- RESUMEN -->

                        <div class="reviews-summary">


                            <div class="reviews-score">

                                <strong>
                                    <?= $curso["rating"] ?>
                                </strong>

                                <div class="stars">
                                    ★★★★★
                                </div>

                                <span>
                                    <?= $curso["total_resenas"] ?>
                                    reseñas
                                </span>

                            </div>


                            <div class="rating-bars">


                                <div class="rating-bar">

                                    <span>
                                        5
                                    </span>

                                    <div>
                                        <span
                                            style="width: 82%;"
                                        ></span>
                                    </div>

                                    <small>
                                        82%
                                    </small>

                                </div>


                                <div class="rating-bar">

                                    <span>
                                        4
                                    </span>

                                    <div>
                                        <span
                                            style="width: 12%;"
                                        ></span>
                                    </div>

                                    <small>
                                        12%
                                    </small>

                                </div>


                                <div class="rating-bar">

                                    <span>
                                        3
                                    </span>

                                    <div>
                                        <span
                                            style="width: 4%;"
                                        ></span>
                                    </div>

                                    <small>
                                        4%
                                    </small>

                                </div>


                            </div>


                        </div>


                        <!-- COMENTARIOS -->

                        <div class="reviews-list">


                            <?php foreach ($resenas as $resena): ?>

                                <article class="review">


                                    <div class="review__avatar">

                                        <?= strtoupper(
                                            substr(
                                                $resena["usuario"],
                                                0,
                                                1
                                            )
                                        ) ?>

                                    </div>


                                    <div class="review__content">

                                        <div class="review__header">

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $resena["usuario"]
                                                ) ?>
                                            </strong>

                                            <span class="stars">
                                                <?= str_repeat(
                                                    "★",
                                                    $resena["rating"]
                                                ) ?>
                                            </span>

                                        </div>


                                        <p>

                                            <?= htmlspecialchars(
                                                $resena["comentario"]
                                            ) ?>

                                        </p>

                                    </div>


                                </article>

                            <?php endforeach; ?>


                        </div>


                    </section>


                </div>


            </div>

        </section>


    </main>


    <!-- =====================================
         FOOTER
    ====================================== -->
<?php include("../Compartidas/Footer.php")?>


    <!-- JAVASCRIPT -->

    <script src="../js/Main.js"></script>

</body>

</html>
