<?php

/*
|--------------------------------------------------------------------------
| CREACIÓN DE CURSO - INSTRUCTOR
|--------------------------------------------------------------------------
|
| Por ahora esta vista utiliza información de ejemplo únicamente
| para visualizar el diseño.
|
| Posteriormente los datos serán proporcionados por el Controller.
|
|--------------------------------------------------------------------------
*/


$nombre = "Carlos";
$apellido = "Ramírez";
$rol = "Instructor";

$inicial = strtoupper(
    substr($nombre, 0, 1)
);


// Datos de ejemplo

$curso = [

    "titulo" => "",
    "descripcion" => "",
    "categoria" => "Seleccionar categoría",
    "nivel" => "Seleccionar nivel",
    "precio" => ""

];


$modulos = [

    [
        "numero" => 1,
        "titulo" => "Introducción a PHP",
        "lecciones" => [
            "¿Qué es PHP?",
            "Instalación y configuración",
            "Primer programa en PHP"
        ]
    ],

    [
        "numero" => 2,
        "titulo" => "Fundamentos de PHP",
        "lecciones" => [
            "Variables y constantes",
            "Tipos de datos",
            "Operadores"
        ]
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
        Crear curso | Academia
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


    <!-- CSS DE CREACIÓN -->

    <link
        rel="stylesheet"
        href="../css/CreacionCurso.css"
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
                class="sidebar__link"
            >

                <span>▣</span>

                Mis cursos

            </a>


            <a
                href="CreacionCurso.php"
                class="sidebar__link active"
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

        <div class="dashboard-content create-course">


            <!-- ENCABEZADO -->

            <section class="create-course__header">


                <div>

                    <span class="section-tag">
                        INSTRUCTOR
                    </span>


                    <h1>
                        Crear nuevo curso
                    </h1>


                    <p>
                        Completa la información de tu curso
                        para comenzar a enseñar.
                    </p>

                </div>


                <a
                    href="MisCursos.php"
                    class="btn btn--outline"
                >
                    ← Volver a mis cursos
                </a>


            </section>



            <!-- =================================================
                 FORMULARIO
            ================================================== -->

            <form
                action="#"
                method="POST"
                class="create-course__form"
            >


                <!-- =============================================
                     INFORMACIÓN BÁSICA
                ============================================== -->

                <section class="create-card">


                    <div class="create-card__header">


                        <div class="create-card__number">
                            01
                        </div>


                        <div>

                            <h2>
                                Información básica
                            </h2>


                            <p>
                                Presenta tu curso a los estudiantes.
                            </p>

                        </div>


                    </div>


                    <div class="create-card__body">


                        <!-- TÍTULO -->

                        <div class="form-group">


                            <label for="titulo">
                                Título del curso
                            </label>


                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                placeholder="Ej. PHP desde cero"
                                value="<?= htmlspecialchars(
                                    $curso["titulo"]
                                ) ?>"
                            >


                            <small>
                                Utiliza un título claro y descriptivo.
                            </small>

                        </div>



                        <!-- DESCRIPCIÓN -->

                        <div class="form-group">


                            <label for="descripcion">
                                Descripción
                            </label>


                            <textarea
                                id="descripcion"
                                name="descripcion"
                                rows="5"
                                placeholder="Describe de qué trata tu curso..."
                            ><?= htmlspecialchars(
                                $curso["descripcion"]
                            ) ?></textarea>


                            <small>
                                Explica qué aprenderán los estudiantes.
                            </small>

                        </div>



                        <!-- CATEGORÍA / NIVEL -->

                        <div class="form-grid">


                            <div class="form-group">


                                <label for="categoria">
                                    Categoría
                                </label>


                                <select
                                    id="categoria"
                                    name="categoria"
                                >

                                    <option>
                                        Seleccionar categoría
                                    </option>

                                    <option>
                                        Programación
                                    </option>

                                    <option>
                                        Diseño
                                    </option>

                                    <option>
                                        Marketing
                                    </option>

                                    <option>
                                        Negocios
                                    </option>

                                    <option>
                                        Fotografía
                                    </option>

                                </select>

                            </div>



                            <div class="form-group">


                                <label for="nivel">
                                    Nivel
                                </label>


                                <select
                                    id="nivel"
                                    name="nivel"
                                >

                                    <option>
                                        Seleccionar nivel
                                    </option>

                                    <option>
                                        Principiante
                                    </option>

                                    <option>
                                        Intermedio
                                    </option>

                                    <option>
                                        Avanzado
                                    </option>

                                </select>

                            </div>


                        </div>


                    </div>


                </section>



                <!-- =============================================
                     IMAGEN
                ============================================== -->

                <section class="create-card">


                    <div class="create-card__header">


                        <div class="create-card__number">
                            02
                        </div>


                        <div>

                            <h2>
                                Imagen del curso
                            </h2>


                            <p>
                                Agrega una imagen que represente tu curso.
                            </p>

                        </div>


                    </div>


                    <div class="create-card__body">


                        <label
                            for="imagen"
                            class="course-upload"
                        >


                            <div class="course-upload__icon">
                                ＋
                            </div>


                            <strong>
                                Subir imagen
                            </strong>


                            <span>
                                PNG, JPG o WEBP · Máximo 5 MB
                            </span>


                            <input
                                type="file"
                                id="imagen"
                                name="imagen"
                                accept="image/png,image/jpeg,image/webp"
                            >


                        </label>


                    </div>


                </section>



                <!-- =============================================
                     CONTENIDO
                ============================================== -->

                <section class="create-card">


                    <div class="create-card__header">


                        <div class="create-card__number">
                            03
                        </div>


                        <div>

                            <h2>
                                Contenido del curso
                            </h2>


                            <p>
                                Organiza las lecciones en módulos.
                            </p>

                        </div>


                    </div>


                    <div class="create-card__body">


                        <div class="modules">


                            <?php foreach (
                                $modulos as $modulo
                            ): ?>


                                <div class="module">


                                    <div class="module__header">


                                        <div>

                                            <span>
                                                MÓDULO
                                                <?= htmlspecialchars(
                                                    $modulo["numero"]
                                                ) ?>
                                            </span>


                                            <h3>

                                                <?= htmlspecialchars(
                                                    $modulo["titulo"]
                                                ) ?>

                                            </h3>

                                        </div>


                                        <button
                                            type="button"
                                            class="module__menu"
                                            aria-label="Opciones del módulo"
                                        >
                                            ⋮
                                        </button>


                                    </div>



                                    <div class="module__lessons">


                                        <?php foreach (
                                            $modulo["lecciones"]
                                            as $leccion
                                        ): ?>


                                            <div class="lesson">


                                                <span class="lesson__drag">
                                                    ☰
                                                </span>


                                                <span class="lesson__icon">
                                                    ▶
                                                </span>


                                                <span class="lesson__title">

                                                    <?= htmlspecialchars(
                                                        $leccion
                                                    ) ?>

                                                </span>


                                                <button
                                                    type="button"
                                                    class="lesson__menu"
                                                    aria-label="Opciones de lección"
                                                >
                                                    ⋮
                                                </button>


                                            </div>


                                        <?php endforeach; ?>


                                    </div>



                                    <button
                                        type="button"
                                        class="add-lesson"
                                    >

                                        ＋ Agregar lección

                                    </button>


                                </div>


                            <?php endforeach; ?>


                        </div>



                        <button
                            type="button"
                            class="add-module"
                        >

                            ＋ Agregar módulo

                        </button>


                    </div>


                </section>



                <!-- =============================================
                     PRECIO
                ============================================== -->

                <section class="create-card">


                    <div class="create-card__header">


                        <div class="create-card__number">
                            04
                        </div>


                        <div>

                            <h2>
                                Precio
                            </h2>


                            <p>
                                Define el precio de tu curso.
                            </p>

                        </div>


                    </div>


                    <div class="create-card__body">


                        <div class="price-input">


                            <span>
                                $
                            </span>


                            <input
                                type="number"
                                name="precio"
                                min="0"
                                step="1"
                                placeholder="0.00"
                                value="<?= htmlspecialchars(
                                    $curso["precio"]
                                ) ?>"
                            >


                            <span>
                                MXN
                            </span>


                        </div>


                    </div>


                </section>



                <!-- =============================================
                     ACCIONES
                ============================================== -->

                <div class="create-course__actions">


                    <a
                        href="MisCursos.php"
                        class="btn btn--secondary btn--large"
                    >
                        Cancelar
                    </a>


                    <button
                        type="button"
                        class="btn btn--outline btn--large"
                    >
                        Guardar borrador
                    </button>


                    <button
                        type="submit"
                        class="btn btn--primary btn--large"
                    >
                        Publicar curso
                    </button>


                </div>


            </form>


        </div>


    </main>


</div>



<script src="../js/Main.js"></script>


</body>

</html>
