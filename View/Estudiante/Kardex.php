<?php
require_once __DIR__ . '/../../config.php';

/*
 * Los datos llegan desde PerfilController.php.
 */

$usuario = $datos['usuario'] ?? [];

$nombre = $usuario['nombre'] ?? '';
$apellido = $usuario['apellido'] ?? '';
$correo = $usuario['correo'] ?? '';
$telefono = $usuario['telefono'] ?? '';
$rol = $usuario['rol'] ?? '';

$kardex = $datos["kardex"] ?? [];

$certificados = $datos["certificados"] ?? [];


$inicial = strtoupper(
    substr($nombre, 0, 1)
);


// =====================================================
// DATOS DE EJEMPLO
// =====================================================

if (empty($kardex)) {

    $kardex = [

        [
            "id" => 1,
            "curso" => "PHP desde cero",
            "categoria" => "Programación",
            "instructor" => "Carlos Ramírez",
            "fecha" => "15/01/2026",
            "progreso" => 72,
            "estado" => "En progreso"
        ],

        [
            "id" => 2,
            "curso" => "JavaScript moderno",
            "categoria" => "Programación",
            "instructor" => "Laura Martínez",
            "fecha" => "03/02/2026",
            "progreso" => 45,
            "estado" => "En progreso"
        ],

        [
            "id" => 3,
            "curso" => "Diseño de interfaces UI/UX",
            "categoria" => "Diseño",
            "instructor" => "Sofía Hernández",
            "fecha" => "20/02/2026",
            "progreso" => 88,
            "estado" => "En progreso"
        ],

        [
            "id" => 4,
            "curso" => "Bases de datos con MySQL",
            "categoria" => "Bases de datos",
            "instructor" => "Miguel Torres",
            "fecha" => "10/11/2025",
            "progreso" => 100,
            "estado" => "Completado"
        ],

        [
            "id" => 5,
            "curso" => "HTML y CSS profesional",
            "categoria" => "Desarrollo web",
            "instructor" => "Daniel García",
            "fecha" => "18/03/2026",
            "progreso" => 20,
            "estado" => "En progreso"
        ],

        [
            "id" => 6,
            "curso" => "Introducción a Python",
            "categoria" => "Programación",
            "instructor" => "Ana López",
            "fecha" => "05/09/2025",
            "progreso" => 100,
            "estado" => "Completado"
        ]

    ];

}


// =====================================================
// CERTIFICADOS DE EJEMPLO
// =====================================================

if (empty($certificados)) {

    $certificados = [

        [
            "curso" => "Bases de datos con MySQL",
            "fecha" => "15/12/2025",
            "codigo" => "CERT-2025-001"
        ],

        [
            "curso" => "Introducción a Python",
            "fecha" => "20/10/2025",
            "codigo" => "CERT-2025-002"
        ]

    ];

}


// =====================================================
// ESTADÍSTICAS
// =====================================================

$totalCursos = count($kardex);

$cursosCompletados = 0;

$cursosEnProgreso = 0;

$totalProgreso = 0;


foreach ($kardex as $curso) {

    $progreso = (int) ($curso["progreso"] ?? 0);

    $totalProgreso += $progreso;


    if ($progreso >= 100) {

        $cursosCompletados++;

    } else {

        $cursosEnProgreso++;

    }

}


$progresoGeneral = $totalCursos > 0
    ? round($totalProgreso / $totalCursos)
    : 0;


$totalCertificados = count($certificados);

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
        Kardex | Academia
    </title>


    <!-- CSS GENERAL -->
    <link rel="stylesheet"
        href="<?= url('View/css/Style.css') ?>">


    <!-- CSS DEL TABLERO -->
    <link rel="stylesheet"
        href="<?= url('View/css/Tablero.css') ?>">


    <!-- CSS DEL KARDEX -->
    <link rel="stylesheet"
        href="<?= url('View/css/Kardex.css') ?>">

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

        <div class="dashboard-content kardex-page">


            <!-- =================================================
                 CABECERA
            ================================================== -->

            <section class="kardex-heading">


                <div>

                    <span class="section-tag">
                        HISTORIAL ACADÉMICO
                    </span>


                    <h1>
                        Mi Kardex
                    </h1>


                    <p>
                        Consulta tu progreso e historial de aprendizaje.
                    </p>

                </div>


                <a
                    href="Cursos.php"
                    class="btn btn--outline"
                >

                    Ver mis cursos

                </a>


            </section>



            <!-- =================================================
                 RESUMEN
            ================================================== -->

            <section class="kardex-summary">


                <div class="kardex-stat">


                    <div class="kardex-stat__icon purple">
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


                <div class="kardex-stat">


                    <div class="kardex-stat__icon blue">
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


                <div class="kardex-stat">


                    <div class="kardex-stat__icon green">
                        ✓
                    </div>


                    <div>

                        <span>
                            Cursos completados
                        </span>


                        <strong>
                            <?= $cursosCompletados ?>
                        </strong>

                    </div>


                </div>


                <div class="kardex-stat">


                    <div class="kardex-stat__icon orange">
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
                 TABLA DE HISTORIAL
            ================================================== -->

            <section class="kardex-panel">


                <div class="kardex-panel__header">


                    <div>

                        <h2>
                            Historial de cursos
                        </h2>


                        <p>
                            Registro de tus cursos y progreso.
                        </p>

                    </div>


                    <div class="kardex-filter">

                        <select
                            aria-label="Filtrar historial"
                        >

                            <option value="todos">
                                Todos los cursos
                            </option>

                            <option value="progreso">
                                En progreso
                            </option>

                            <option value="completados">
                                Completados
                            </option>

                        </select>

                    </div>


                </div>



                <div class="kardex-table-wrapper">


                    <table class="kardex-table">


                        <thead>

                            <tr>

                                <th>
                                    Curso
                                </th>

                                <th>
                                    Instructor
                                </th>

                                <th>
                                    Inscripción
                                </th>

                                <th>
                                    Progreso
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th>
                                    Acción
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php foreach ($kardex as $curso): ?>


                                <?php

                                $progreso =
                                    (int) ($curso["progreso"] ?? 0);

                                $completado =
                                    $progreso >= 100;

                                ?>


                                <tr>


                                    <!-- CURSO -->

                                    <td>

                                        <div
                                            class="
                                                kardex-course
                                            "
                                        >


                                            <div
                                                class="
                                                    kardex-course__icon
                                                "
                                            >

                                                <?= htmlspecialchars(
                                                    substr(
                                                        $curso["curso"],
                                                        0,
                                                        1
                                                    )
                                                ) ?>

                                            </div>


                                            <div>

                                                <strong>

                                                    <?= htmlspecialchars(
                                                        $curso["curso"]
                                                    ) ?>

                                                </strong>


                                                <span>

                                                    <?= htmlspecialchars(
                                                        $curso["categoria"]
                                                    ) ?>

                                                </span>

                                            </div>


                                        </div>

                                    </td>



                                    <!-- INSTRUCTOR -->

                                    <td>

                                        <span class="table-text">

                                            <?= htmlspecialchars(
                                                $curso["instructor"]
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- FECHA -->

                                    <td>

                                        <span class="table-text">

                                            <?= htmlspecialchars(
                                                $curso["fecha"]
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- PROGRESO -->

                                    <td>


                                        <div
                                            class="
                                                table-progress
                                            "
                                        >


                                            <div
                                                class="
                                                    table-progress__header
                                                "
                                            >

                                                <span>
                                                    <?= $progreso ?>%
                                                </span>

                                            </div>


                                            <div
                                                class="
                                                    table-progress__bar
                                                "
                                            >

                                                <span
                                                    style="
                                                        width:
                                                        <?= $progreso ?>%;
                                                    "
                                                ></span>

                                            </div>


                                        </div>


                                    </td>



                                    <!-- ESTADO -->

                                    <td>


                                        <span
                                            class="
                                                kardex-status
                                                <?= $completado
                                                    ? "completed"
                                                    : "progress" ?>
                                            "
                                        >

                                            <?php if ($completado): ?>

                                                ✓ Completado

                                            <?php else: ?>

                                                ● En progreso

                                            <?php endif; ?>

                                        </span>


                                    </td>



                                    <!-- ACCIÓN -->

                                    <td>

                                        <a
                                            href="Cursos.php"
                                            class="table-action"
                                        >

                                            Ver

                                        </a>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>


                    </table>


                </div>


            </section>



            <!-- =================================================
                 CERTIFICADOS
            ================================================== -->

            <section class="certificates-panel">


                <div class="certificates-panel__header">


                    <div>

                        <h2>
                            Mis certificados
                        </h2>


                        <p>
                            Cursos que has completado satisfactoriamente.
                        </p>

                    </div>


                    <span class="certificate-count">

                        <?= $totalCertificados ?>

                        certificados

                    </span>


                </div>



                <div class="certificates-grid">


                    <?php foreach ($certificados as $certificado): ?>


                        <article class="certificate-card">


                            <div class="certificate-card__icon">
                                ✓
                            </div>


                            <div
                                class="
                                    certificate-card__content
                                "
                            >

                                <span>
                                    CERTIFICADO DE FINALIZACIÓN
                                </span>


                                <h3>

                                    <?= htmlspecialchars(
                                        $certificado["curso"]
                                    ) ?>

                                </h3>


                                <p>

                                    Completado el

                                    <?= htmlspecialchars(
                                        $certificado["fecha"]
                                    ) ?>

                                </p>


                                <small>

                                    <?= htmlspecialchars(
                                        $certificado["codigo"]
                                    ) ?>

                                </small>

                            </div>


                            <button
                                type="button"
                                class="certificate-download"
                            >

                                ↓

                            </button>


                        </article>


                    <?php endforeach; ?>


                </div>


            </section>


        </div>


    </main>


</div>



<script src="<?= url('View/js/Main.js') ?>"></script>

</body>

</html>
