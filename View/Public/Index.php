<?php
    // En el futuro estos datos vendrán del Controller.
    $cursosDestacados = [
        [
            "titulo" => "Introducción a JavaScript",
            "instructor" => "Juan Pérez",
            "calificacion" => "4.8",
            "precio" => "$499",
            "imagen" => "https://placehold.co/600x350/1e293b/ffffff?text=JavaScript"
        ],
        [
            "titulo" => "Diseño UI/UX desde cero",
            "instructor" => "Ana López",
            "calificacion" => "4.9",
            "precio" => "$599",
            "imagen" => "https://placehold.co/600x350/334155/ffffff?text=UI%2FUX"
        ],
        [
            "titulo" => "Python para principiantes",
            "instructor" => "Carlos Ramírez",
            "calificacion" => "4.7",
            "precio" => "$449",
            "imagen" => "https://placehold.co/600x350/475569/ffffff?text=Python"
        ],
        [
            "titulo" => "Marketing Digital",
            "instructor" => "María García",
            "calificacion" => "4.9",
            "precio" => "$399",
            "imagen" => "https://placehold.co/600x350/64748b/ffffff?text=Marketing"
        ]
    ];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Academia | Aprende a tu ritmo</title>

    <link rel="stylesheet" href="../css/Style.css">
    <link rel="stylesheet" href="../css/Index.css">
</head>

<body>

    <!-- =========================
         NAVBAR
    ========================== -->
    <?php include("../Compartidas/Header.php")?>

    <!-- =========================
         HERO
    ========================== -->
    <main>

        <section class="hero">

            <div class="hero__content">

                <span class="hero__tag">
                    APRENDE ALGO NUEVO
                </span>

                <h1>
                    Convierte tu curiosidad
                    en nuevas habilidades.
                </h1>

                <p>
                    Descubre cursos creados para aprender,
                    practicar y avanzar a tu propio ritmo.
                </p>

                <div class="hero__actions">

                    <a
                        href="../Public/Categorias.php"
                        class="btn btn--primary btn--large"
                    >
                        Explorar cursos
                    </a>

                    <a
                        href="../Auth/Registro.php"
                        class="btn btn--outline btn--large"
                    >
                        Crear una cuenta
                    </a>

                </div>

            </div>


            <div class="hero__visual">

                <div class="hero__card">

                    <div class="hero__card-icon">
                        ✦
                    </div>

                    <span>Tu próximo aprendizaje</span>

                    <strong>
                        comienza aquí.
                    </strong>

                </div>

            </div>

        </section>


        <!-- =========================
             CATEGORÍAS
        ========================== -->
        <section class="categories">

            <div class="section-heading">

                <span class="section-heading__tag">
                    EXPLORA
                </span>

                <h2>
                    Encuentra algo que aprender
                </h2>


            </div>


            <div class="categories__grid">

                <a href="../Public/Busqueda.php?categoria=programacion"
                   class="category-card">

                    <span class="category-card__icon">
                        &lt;/&gt;
                    </span>

                    <h3>Programación</h3>

                    <p>
                        Desarrollo web, software y más.
                    </p>

                </a>


                <a href="../Public/Busqueda.php?categoria=diseno"
                   class="category-card">

                    <span class="category-card__icon">
                        ◈
                    </span>

                    <h3>Diseño</h3>

                    <p>
                        UI, UX, ilustración y creatividad.
                    </p>

                </a>


                <a href="../Public/Busqueda.php?categoria=marketing"
                   class="category-card">

                    <span class="category-card__icon">
                        ↗
                    </span>

                    <h3>Marketing</h3>

                    <p>
                        Estrategias para crecer y vender.
                    </p>

                </a>


                <a href="../Public/Busqueda.php?categoria=idiomas"
                   class="category-card">

                    <span class="category-card__icon">
                        Aa
                    </span>

                    <h3>Idiomas</h3>

                    <p>
                        Aprende nuevos idiomas.
                    </p>

                </a>

            </div>

        </section>


        <!-- =========================
             CURSOS DESTACADOS
        ========================== -->
        <section class="featured">

            <div class="section-heading section-heading--row">

                <div>

                    <span class="section-heading__tag">
                        RECOMENDADOS
                    </span>

                    <h2>
                        Cursos destacados
                    </h2>

                </div>

                <a href="Categorias.php" class="link">
                    Ver todos →
                </a>

            </div>


            <div class="courses-grid">

                <?php foreach ($cursosDestacados as $curso): ?>

                    <article class="course-card">

                        <div class="course-card__image">

                            <img
                                src="<?= htmlspecialchars($curso["imagen"]) ?>"
                                alt="<?= htmlspecialchars($curso["titulo"]) ?>"
                            >

                        </div>


                        <div class="course-card__content">

                            <span class="course-card__category">
                                Curso
                            </span>

                            <h3>
                                <?= htmlspecialchars($curso["titulo"]) ?>
                            </h3>

                            <p class="course-card__instructor">
                                <?= htmlspecialchars($curso["instructor"]) ?>
                            </p>

                            <div class="course-card__info">

                                <span>
                                    ★ <?= htmlspecialchars($curso["calificacion"]) ?>
                                </span>

                                <strong>
                                    <?= htmlspecialchars($curso["precio"]) ?>
                                </strong>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>


        <!-- =========================
             BENEFICIOS
        ========================== -->
        <section class="benefits">

            <div class="section-heading">

                <span class="section-heading__tag">
                    NUESTRA PLATAFORMA
                </span>

                <h2>
                    Aprende de una manera diferente
                </h2>

            </div>


            <div class="benefits__grid">

                <article class="benefit">

                    <div class="benefit__icon">
                        01
                    </div>

                    <h3>
                        Aprende
                    </h3>

                    <p>
                        Accede a contenido diseñado para
                        ayudarte a desarrollar nuevas habilidades.
                    </p>

                </article>


                <article class="benefit">

                    <div class="benefit__icon">
                        02
                    </div>

                    <h3>
                        Practica
                    </h3>

                    <p>
                        Resuelve preguntas y actividades
                        mientras avanzas por cada curso.
                    </p>

                </article>


                <article class="benefit">

                    <div class="benefit__icon">
                        03
                    </div>

                    <h3>
                        Certifícate
                    </h3>

                    <p>
                        Completa tus cursos y demuestra
                        todo lo que has aprendido.
                    </p>

                </article>

            </div>

        </section>


        <!-- =========================
             CTA
        ========================== -->
        <section class="cta">

            <div class="cta__content">

                <span>
                    ¿LISTO PARA COMENZAR?
                </span>

                <h2>
                    Tu siguiente habilidad
                    está a un curso de distancia.
                </h2>

                <a
                    href="Registro.php"
                    class="btn btn--light btn--large"
                >
                    Crear mi cuenta
                </a>

            </div>

        </section>

    </main>


    <!-- =========================
         FOOTER
    ========================== -->
<?php include("../Compartidas/Footer.php")?>


    <script src="View/js/Main.js"></script>

</body>
</html>