<?php
require_once __DIR__ . '/../../config.php';

// DATOS DEL INSTRUCTOR
$usuario = $datos['usuario'] ?? [];

$nombre = $usuario['nombre'] ?? '';
$apellido = $usuario['apellido'] ?? '';
$correo = $usuario['correo'] ?? '';
$telefono = $usuario['telefono'] ?? '';
$rol = $usuario['rol'] ?? '';

$inicial = strtoupper(
    substr($nombre, 0, 1)
);

// CURSOS
$cursos = $datos['cursos'] ?? [];

// ESTADÍSTICAS
$estadisticas = $datos['estadisticas'] ?? [];

$totalCursos = $estadisticas['total_cursos'] ?? 0;

$cursosPublicados = $estadisticas['publicados'] ?? 0;

$cursosBorrador = $estadisticas['borradores'] ?? 0;

$totalEstudiantes = $estadisticas['estudiantes'] ?? 0;

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
     <link rel="stylesheet"
        href="<?= url('View/css/Style.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/Tablero.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/MisCursos.css') ?>">

</head>


<body class="dashboard-body">


<div class="dashboard">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <?php require_once __DIR__ . "/../Compartidas/SideBar.php"; ?>

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
                    href="<?= htmlspecialchars(url('Controller/CrearCursosController.php'), ENT_QUOTES, 'UTF-8') ?>"
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
                            <?= $totalCursos ?>
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
                            <?= $cursosPublicados ?>
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
                            <?= $cursosBorrador ?>
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
                            <?= $totalEstudiantes ?>
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
                <?php if (!empty($cursos)): ?>

                    <?php foreach ($cursos as $curso): ?>
                        <article class="instructor-course">

                            <!-- IMAGEN -->
                            <div class="
                                instructor-course__image
                                <?= htmlspecialchars($curso['color']) ?>
                            ">

                                <span>
                                    <?= htmlspecialchars($curso['categoria']) ?>
                                </span>

                                <?php if (!empty($curso['imagen_url'])): ?>

                                    <img
                                        src="<?= htmlspecialchars($curso['imagen_url']) ?>"
                                        alt="<?= htmlspecialchars($curso['titulo']) ?>"
                                        style="width:100%; height:100%; object-fit:cover;"
                                    >

                                <?php else: ?>

                                    <div class="course-image-placeholder">
                                        ACADEMIA
                                    </div>

                                <?php endif; ?>

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

                                <div class="instructor-course__meta">

                                    <span>
                                        👥

                                        <?= (int) $curso['estudiantes'] ?>

                                        estudiantes
                                    </span>

                                    <span>
                                        $

                                        <?= number_format(
                                            $curso['precio'],
                                            2
                                        ) ?>
                                    </span>

                                </div>

                                <div class="instructor-course__details">
                                    <span>
                                        <?= (int) $curso['modulos'] ?> módulos
                                    </span>
                                    <span>
                                        <?= (int) $curso['lecciones'] ?> lecciones
                                    </span>
                                    <span>
                                        <?= htmlspecialchars($curso['nivel'] ?? 'Principiante') ?>
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
                            <div class="instructor-course__actions">


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

                <?php else: ?>
                <div class="courses-empty">

                    <div class="courses-empty__icon">
                        ▣
                    </div>

                    <h2>
                        Todavía no has creado cursos
                    </h2>

                    <p>
                        Comienza creando tu primer curso y comparte
                        tus conocimientos con los estudiantes.
                    </p>

                    <a
                        href="CrearCursoController.php"
                        class="btn btn--primary"
                    >
                        Crear mi primer curso
                    </a>

                </div>

            <?php endif; ?> 


            </section>
        </div>
    </main>
</div>

<script src="<?= url('View/js/Main.js') ?>"></script>
</body>

</html>