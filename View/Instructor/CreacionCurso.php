<?php

    require_once __DIR__ . '/../../config.php';

    $usuario = $datos['usuario'] ?? [];

    $nombre = $usuario['nombre'] ?? '';
    $apellido = $usuario['apellido'] ?? '';
    $correo = $usuario['correo'] ?? '';
    $rol = $usuario['rol'] ?? '';

    $inicial = strtoupper(substr($nombre, 0, 1));

    $modulos = $valores['modulos'] ?? [];

    if (empty($modulos)) {
        $modulos = [
            [
                'titulo' => '',
                'lecciones' => ['']
            ]
        ];
    }

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
     <link rel="stylesheet"
        href="<?= url('View/css/Style.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/Tablero.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/CreacionCurso.css') ?>">

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
                    href="<?= url('Controller/MisCursosController.php') ?>"
                    class="btn btn--outline"
                >
                    ← Volver a mis cursos
                </a>
            </section>

            <!-- =================================================
                 FORMULARIO
            ================================================== -->
                <form
                    action="<?= url('Controller/CursoController.php') ?>"
                    method="POST"
                    enctype="multipart/form-data"
                    class="create-course__form"
                >
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($csrfToken) ?>"
                    >
                    <!--Mensajes de error-->
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($mensaje) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-error">
                            <?php foreach ($errores as $error): ?>
                                <p>
                                    <?= htmlspecialchars($error) ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

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
                                maxlength="200"
                                required
                                placeholder="Ej. PHP desde cero"
                                value="<?= htmlspecialchars($valores['titulo'] ?? '') ?>"
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
                                required
                                placeholder="Describe de qué trata tu curso..."
                            ><?= htmlspecialchars($valores['descripcion'] ?? '') ?>
                            </textarea>
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
                                    required
                                >
                                    <option value="">
                                        Seleccionar categoría
                                    </option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option
                                            value="<?= (int)$categoria['id_categoria'] ?>"
                                            <?= (
                                                (string)($valores['categoria'] ?? '') ===
                                                (string)$categoria['id_categoria']
                                            ) ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars(
                                                $categoria['nombre_categoria']
                                            ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nivel">
                                    Nivel
                                </label>
                                <select
                                    id="nivel"
                                    name="nivel"
                                    required
                                >
                                    <?php
                                    $niveles = [
                                        'Principiante',
                                        'Intermedio',
                                        'Avanzado'
                                    ];
                                    ?>
                                    <option value="">
                                        Seleccionar nivel
                                    </option>
                                    <?php foreach ($niveles as $nivel): ?>
                                        <option
                                            value="<?= $nivel ?>"
                                            <?= ($valores['nivel'] ?? '') === $nivel
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= $nivel ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- =============================================
                    VIDEO DE PRESENTACIÓN
                ============================================== -->
                <section class="create-card">
                    <div class="create-card__header">
                        <div class="create-card__number">
                            02
                        </div>
                        <div>
                            <h2>
                                Video de presentación
                            </h2>
                            <p>
                                Presenta tu curso a los estudiantes.
                            </p>
                        </div>
                    </div>

                    <div class="create-card__body">
                        <label
                            for="video"
                            class="course-upload"
                        >
                            <div class="course-upload__icon">
                                ▶
                            </div>
                            <strong>
                                Seleccionar video
                            </strong>
                            <span>
                                MP4 o WEBM · Máximo 100 MB
                            </span>
                            <input
                                type="file"
                                id="video"
                                name="video"
                                accept="video/mp4,video/webm"
                                required
                            >
                        </label>
                        <div id="videoPreviewContainer" hidden>
                            <video
                                id="videoPreview"
                                controls
                                style="width:100%; max-height:350px; margin-top:20px;"
                            ></video>
                            <p id="videoName"></p>
                        </div>
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
                        <div class="modules" id="modulesContainer">
                            <?php foreach ($modulos as $i => $modulo): ?>
                                <div class="module">
                                    <div class="module__header">
                                        <div>
                                            <span>
                                                MÓDULO <?= $i + 1 ?>
                                            </span>
                                            <input
                                                type="text"
                                                name="modulos[<?= $i ?>][titulo]"
                                                value="<?= htmlspecialchars($modulo['titulo'] ?? '') ?>"
                                                placeholder="Nombre del módulo"
                                                maxlength="200"
                                                required
                                            >
                                        </div>
                                        <button
                                            type="button"
                                            class="module__menu remove-module"
                                            aria-label="Eliminar módulo"
                                        >
                                            ×
                                        </button>
                                    </div>
                                    <div class="module__lessons">
                                        <?php foreach (($modulo['lecciones'] ?? []) as $leccion): ?>
                                            <div class="lesson">
                                                <span class="lesson__icon">▶</span>
                                                <input
                                                    type="text"
                                                    name="modulos[<?= $i ?>][lecciones][]"
                                                    value="<?= htmlspecialchars($leccion) ?>"
                                                    placeholder="Nombre de la lección"
                                                    maxlength="200"
                                                    required
                                                >
                                                <button
                                                    type="button"
                                                    class="lesson__menu remove-lesson"
                                                    aria-label="Eliminar lección"
                                                >
                                                    ×
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
                            id="addModule"
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
                                id="precio"
                                name="precio"
                                min="0"
                                max="99999999.99"
                                step="0.01"
                                required
                                placeholder="0.00"
                                value="<?= htmlspecialchars($valores['precio'] ?? '0.00') ?>"
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
                        href="<?= url('Controller/MisCursosController.php') ?>"
                        class="btn btn--secondary btn--large"
                    >
                        Cancelar
                    </a>
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
<script src="<?= url('View/js/Main.js') ?>"></script>
<script src="<?= url('View/js/CreacionCurso.js') ?>"></script>

</body>

</html>
