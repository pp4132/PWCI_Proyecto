<?php

    /*
     * =========================================
     * DATOS TEMPORALES
     * =========================================
     *
     * Por ahora utilizamos datos estáticos
     * para construir el prototipo.
     *
     * Posteriormente estos datos vendrán
     * desde el Controller.
     */

    $categorias = [

        [
            "nombre" => "Programación",
            "descripcion" => "Desarrolla aplicaciones, sitios web y software.",
            "icono" => "</>",
            "cursos" => 24
        ],

        [
            "nombre" => "Diseño",
            "descripcion" => "Aprende diseño gráfico, UI/UX y herramientas creativas.",
            "icono" => "✦",
            "cursos" => 18
        ],

        [
            "nombre" => "Negocios",
            "descripcion" => "Desarrolla habilidades para emprender y administrar.",
            "icono" => "$",
            "cursos" => 15
        ],

        [
            "nombre" => "Marketing",
            "descripcion" => "Aprende estrategias de marketing digital y ventas.",
            "icono" => "↗",
            "cursos" => 12
        ],

        [
            "nombre" => "Fotografía",
            "descripcion" => "Mejora tus habilidades fotográficas y audiovisuales.",
            "icono" => "◉",
            "cursos" => 9
        ],

        [
            "nombre" => "Educación",
            "descripcion" => "Encuentra cursos para mejorar tus métodos de enseñanza.",
            "icono" => "▣",
            "cursos" => 11
        ],

        [
            "nombre" => "Idiomas",
            "descripcion" => "Aprende nuevos idiomas y mejora tu comunicación.",
            "icono" => "A",
            "cursos" => 16
        ],

        [
            "nombre" => "Desarrollo personal",
            "descripcion" => "Mejora tus hábitos, productividad y habilidades.",
            "icono" => "★",
            "cursos" => 13
        ]

    ];


    /*
     * =========================================
     * CURSOS DESTACADOS
     * =========================================
     *
     * Datos temporales para el prototipo.
     */

    $cursosDestacados = [

        [
            "titulo" => "JavaScript desde cero",
            "categoria" => "Programación",
            "instructor" => "Juan Pérez",
            "rating" => "4.8",
            "estudiantes" => 325,
            "precio" => "$299"
        ],

        [
            "titulo" => "Diseño UI/UX moderno",
            "categoria" => "Diseño",
            "instructor" => "María López",
            "rating" => "4.9",
            "estudiantes" => 214,
            "precio" => "$349"
        ],

        [
            "titulo" => "Marketing Digital",
            "categoria" => "Marketing",
            "instructor" => "Carlos Ramírez",
            "rating" => "4.7",
            "estudiantes" => 187,
            "precio" => "$249"
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

    <title>Categorías | Academia</title>


    <!-- CSS GENERAL -->

    <link
        rel="stylesheet"
        href="../css/Style.css"
    >


    <!-- CSS DE CATEGORÍAS -->

    <link
        rel="stylesheet"
        href="../css/Categorias.css"
    >

</head>


<body>


    <!-- =====================================
         NAVBAR
    ====================================== -->
    <?php include("../Compartidas/Header.php")?>


    <!-- =====================================
         CONTENIDO PRINCIPAL
    ====================================== -->

    <main>


        <!-- =================================
             HERO
        ================================== -->

        <section class="categories-hero">

            <div class="container categories-hero__content">


                <span class="section-tag">
                    EXPLORA Y APRENDE
                </span>


                <h1>
                    Encuentra tu próxima
                    <span>habilidad.</span>
                </h1>


                <p>
                    Explora nuestras categorías y descubre
                    cursos creados para ayudarte a alcanzar
                    tus objetivos.
                </p>


                <!-- BUSCADOR -->

                <form
                    action="../Public/Busqueda.php"
                    method="GET"
                    class="categories-search"
                >

                    <input
                        type="search"
                        name="q"
                        placeholder="¿Qué quieres aprender?"
                        aria-label="Buscar cursos"
                    >

                    <button
                        type="submit"
                        class="btn btn--primary"
                    >
                        Buscar cursos
                    </button>

                </form>


            </div>

        </section>


        <!-- =================================
             CATEGORÍAS
        ================================== -->

        <section class="categories-section">

            <div class="container">


                <!-- ENCABEZADO -->

                <div class="section-header">

                    <div>

                        <span class="section-tag">
                            CATEGORÍAS
                        </span>

                        <h2>
                            Explora por área
                        </h2>

                    </div>

                    <p>
                        <?= count($categorias) ?>
                        áreas para comenzar a aprender.
                    </p>

                </div>


                <!-- GRID -->

                <div class="categories-grid">


                    <?php foreach ($categorias as $categoria): ?>

                        <a
                            href="Busqueda.php?categoria=<?= urlencode($categoria["nombre"]) ?>"
                            class="category-card"
                        >


                            <!-- ICONO -->

                            <div class="category-card__icon">

                                <?= $categoria["icono"] ?>

                            </div>


                            <!-- CONTENIDO -->

                            <div class="category-card__content">

                                <h3>
                                    <?= htmlspecialchars($categoria["nombre"]) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars($categoria["descripcion"]) ?>
                                </p>

                                <span class="category-card__courses">

                                    <?= $categoria["cursos"] ?>
                                    cursos

                                    <span>
                                        →
                                    </span>

                                </span>

                            </div>


                        </a>

                    <?php endforeach; ?>


                </div>


            </div>

        </section>


        <!-- =================================
             CURSOS DESTACADOS
        ================================== -->

        <section class="featured-courses">

            <div class="container">


                <!-- ENCABEZADO -->

                <div class="section-header">

                    <div>

                        <span class="section-tag">
                            RECOMENDADOS
                        </span>

                        <h2>
                            Cursos destacados
                        </h2>

                    </div>


                    <a
                        href="../Public/Busqueda.php"
                        class="section-link"
                    >
                        Ver todos →
                    </a>

                </div>


                <!-- CURSOS -->

                <div class="course-grid">


                    <?php foreach ($cursosDestacados as $curso): ?>

                        <article class="course-card">


                            <!-- IMAGEN -->

                            <div class="course-card__image">

                                <span>
                                    <?= htmlspecialchars($curso["categoria"]) ?>
                                </span>

                            </div>


                            <!-- CONTENIDO -->

                            <div class="course-card__content">


                                <span class="course-card__category">

                                    <?= htmlspecialchars($curso["categoria"]) ?>

                                </span>


                                <h3>

                                    <?= htmlspecialchars($curso["titulo"]) ?>

                                </h3>


                                <p class="course-card__instructor">

                                    <?= htmlspecialchars($curso["instructor"]) ?>

                                </p>


                                <div class="course-card__rating">

                                    <strong>
                                        <?= $curso["rating"] ?>
                                    </strong>

                                    <span>
                                        ★★★★★
                                    </span>

                                    <small>
                                        (<?= $curso["estudiantes"] ?>)
                                    </small>

                                </div>


                                <div class="course-card__footer">

                                    <strong>
                                        <?= $curso["precio"] ?>
                                    </strong>

                                    <a
                                        href="../Public/CursoPreambulo.php"
                                        class="course-card__link"
                                    >
                                        Ver curso →
                                    </a>

                                </div>


                            </div>


                        </article>

                    <?php endforeach; ?>


                </div>


            </div>

        </section>


        <!-- =================================
             CTA
        ================================== -->

        <section class="categories-cta">

            <div class="container categories-cta__content">


                <div>

                    <span class="section-tag">
                        ¿NO SABES POR DÓNDE EMPEZAR?
                    </span>

                    <h2>
                        Encuentra el curso
                        perfecto para ti.
                    </h2>

                    <p>
                        Utiliza nuestro buscador para encontrar
                        exactamente lo que quieres aprender.
                    </p>

                </div>


                <a
                    href="../Public/Busqueda.php"
                    class="btn btn--light"
                >
                    Explorar cursos
                </a>


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
