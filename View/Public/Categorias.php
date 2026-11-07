<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/CategoriasController.php';

try {

    $controller = new CategoriasController();

    $datos = $controller->index();

    $categorias = $datos['categorias'];
    $cursosDestacados = $datos['cursosDestacados'];

    $error = null;

} catch (Throwable $e) {

    error_log(
        "Error al cargar categorías: " . $e->getMessage()
    );

    $categorias = [];
    $cursosDestacados = [];

    $error = 'No fue posible cargar las categorías en este momento.';

}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categorías | Academia</title>

    <link rel="stylesheet" href="<?= url('View/css/Style.css') ?>">

    <link rel="stylesheet" href="<?= url('View/css/Categorias.css') ?>">
</head>

<body>
    <?php require_once __DIR__ . '/../Compartidas/Header.php'; ?>

    <main>
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


        <!-- CATEGORÍAS -->
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

                    <?php if (!empty($categorias)): ?>

                        <?php
                            $iconos = [
                                '&lt;/&gt;',
                                '✦',
                                '$',
                                '↗',
                                '◉',
                                '▣',
                                'A',
                                '★'
                            ];
                        ?>

                        <?php foreach ($categorias as $indice => $categoria): ?>

                            <a
                                href="<?= url(
                                    'View/Public/Busqueda.php?id_categoria=' .
                                    (int)$categoria['id_categoria']
                                ) ?>"
                                class="category-card"
                            >

                                <div class="category-card__icon">

                                    <?= $iconos[$indice % count($iconos)] ?>

                                </div>

                                <div class="category-card__content">

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

                                    <span class="category-card__courses">

                                        <?= (int)$categoria['total_cursos'] ?>

                                        cursos

                                        <span>
                                            →
                                        </span>

                                    </span>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p>
                            Todavía no hay categorías registradas.
                        </p>

                    <?php endif; ?>

                </div>
            </div>
        </section>

        <!-- CURSOS DESTACADOS -->
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

                    <?php if (!empty($cursosDestacados)): ?>

                        <?php foreach ($cursosDestacados as $curso): ?>

                            <?php

                                $imagen = !empty($curso['imagen_url'])
                                    ? $curso['imagen_url']
                                    : 'https://placehold.co/600x350/1e293b/ffffff?text=Curso';

                                $instructor =
                                    $curso['nombre_instructor'] . ' ' .
                                    $curso['apellidos_instructor'];

                            ?>

                            <article class="course-card">

                                <!-- IMAGEN -->

                                <div class="course-card__image">

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

                                    <span>
                                        <?= htmlspecialchars(
                                            $curso['nombre_categoria'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </div>

                                <!-- CONTENIDO -->

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

                                    <div class="course-card__rating">

                                        <strong>
                                            <?= number_format(
                                                (float)$curso['promedio_general'],
                                                1
                                            ) ?>
                                        </strong>

                                        <span>
                                            ★★★★★
                                        </span>

                                        <small>
                                            (<?= (int)$curso['total_estudiantes'] ?>)
                                        </small>

                                    </div>

                                    <div class="course-card__footer">

                                        <strong>

                                            $<?= number_format(
                                                (float)$curso['precio_curso'],
                                                2
                                            ) ?> MXN

                                        </strong>

                                        <a
                                            href="<?= url(
                                                'View/Public/CursoPreambulo.php?id=' .
                                                (int)$curso['id_curso']
                                            ) ?>"
                                            class="course-card__link"
                                        >
                                            Ver curso →
                                        </a>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p>
                            Todavía no hay cursos disponibles.
                        </p>

                    <?php endif; ?>

                </div>
            </div>
        </section>

        <!-- CTA -->
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

    <?php require_once __DIR__ . '/../Compartidas/Footer.php'; ?>
   
    <script src="<?= url('View/js/Main.js') ?>"></script>
</body>
</html>
