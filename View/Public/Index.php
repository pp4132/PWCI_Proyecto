<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/IndexController.php';

try {
    $controller = new IndexController();
    $datos = $controller->index();
    $categorias = $datos['categorias'];
    $cursosDestacados = $datos['cursosDestacados'];
    $error = null;
} catch (Throwable $e) {
    error_log(
        "Error al cargar Index.php: " . $e->getMessage()
    );

    $categorias = [];
    $cursosDestacados = [];
    $error = 'No fue posible cargar el contenido. Intenta más tarde.';
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Academia | Aprende a tu ritmo</title>

    <link rel="stylesheet" href="<?= url('View/css/Style.css') ?>">
    <link rel="stylesheet" href="<?= url('View/css/Index.css') ?>">
</head>

<body>
    <?php require_once __DIR__ . "/../Compartidas/Header.php"; ?>

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
                    <a href="<?= url('View/Public/Categorias.php') ?>"
                        class="btn btn--primary btn--large">
                        Explorar cursos
                    </a>

                    <a href="<?= url('View/Auth/Registro.php') ?>"
                        class="btn btn--outline btn--large">
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

        <!-- CATEGORÍAS -->
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

                <?php if (!empty($categorias)): ?>

                    <?php
                        $iconos = ['&lt;/&gt;', '◈', '↗', 'Aa'];
                    ?>

                    <?php foreach ($categorias as $indice => $categoria): ?>

                        <a
                            href="<?= url('View/Public/Busqueda.php?id_categoria=' . (int)$categoria['id_categoria']) ?>"
                            class="category-card"
                        >

                            <span class="category-card__icon">
                                <?= $iconos[$indice % count($iconos)] ?>
                            </span>

                            <h3>
                                <?= htmlspecialchars(
                                    $categoria['nombre_categoria'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars(
                                    $categoria['descripcion'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                        </a>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>
                        No hay categorías disponibles actualmente.
                    </p>

                <?php endif; ?>

            </div>

        </section>

        <!-- CURSOS DESTACADOS -->
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

                <a href="<?= url('View/Public/Categorías.php') ?>"
                class="link">
                    Ver todos →
                </a>

            </div>

            <div class="courses-grid">

                <?php if (!empty($cursosDestacados)): ?>

                    <?php foreach ($cursosDestacados as $curso): ?>

                        <?php

                            $imagen = !empty($curso['imagen_url'])
                                ? $curso['imagen_url']
                                : 'https://cdn-icons-png.flaticon.com/512/6681/6681144.png';

                            $instructor =
                                $curso['nombre_instructor'] . ' ' .
                                $curso['apellidos_instructor'];

                        ?>

                        <article class="course-card">

                            <a
                                href="<?= url('View/Public/CursoPreambulo.php?id=' . (int)$curso['id_curso']) ?>"
                                class="course-card__image"
                            >

                                <img
                                    src="<?= htmlspecialchars(
                                        $imagen,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $curso['nombre_curso'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    loading="lazy"
                                >

                            </a>

                            <div class="course-card__content">

                                <span class="course-card__category">

                                    <?= htmlspecialchars(
                                        $curso['nombre_categoria'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                                <h3>

                                    <?= htmlspecialchars(
                                        $curso['nombre_curso'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </h3>

                                <p class="course-card__instructor">

                                    <?= htmlspecialchars(
                                        $instructor,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </p>

                                <div class="course-card__info">

                                    <span>
                                        ★ <?= number_format(
                                            (float)$curso['promedio_general'],
                                            1
                                        ) ?>
                                    </span>

                                    <strong>
                                        $<?= number_format(
                                            (float)$curso['precio_curso'],
                                            2
                                        ) ?> MXN
                                    </strong>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>
                        Actualmente no hay cursos destacados.
                    </p>

                <?php endif; ?>

            </div>

        </section>

        <!-- BENEFICIOS -->
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

        <!-- CTA -->
        <section class="cta">
            <div class="cta__content">
                <span>
                    ¿LISTO PARA COMENZAR?
                </span>

                <h2>
                    Tu siguiente habilidad
                    está a un curso de distancia.
                </h2>

                <a href="<?= url('View/Auth/Registro.php') ?>"
                    class="btn btn--light btn--large">
                    Crear mi cuenta
                </a>
            </div>
        </section>
    </main>
    <?php require_once __DIR__ . "/../Compartidas/Footer.php"; ?>

    <script src="<?= url('View/js/Main.js') ?>"></script>
</body>
</html>