<?php

/*
|--------------------------------------------------------------------------
| CONSULTA DE REPORTES
|--------------------------------------------------------------------------
|
| Esta vista recibe toda la información desde el Controller.
|
| Datos esperados:
|
| $datos["usuario"]
| $datos["reportes"]
| $datos["estadisticas"]
| $datos["actividad"]
|
|--------------------------------------------------------------------------
*/


$usuario = $datos["usuario"] ?? [];

$reportes = $datos["reportes"] ?? [];

$estadisticas = $datos["estadisticas"] ?? [];

$actividad = $datos["actividad"] ?? [];


// =====================================================
// DATOS DEL ADMINISTRADOR
// =====================================================

$nombre = $usuario["nombre"] ?? "Administrador";

$apellido = $usuario["apellido"] ?? "";

$rol = $usuario["rol"] ?? "Administrador";


$inicial = strtoupper(
    substr($nombre, 0, 1)
);


// =====================================================
// DATOS DE PRUEBA
// =====================================================

if (empty($estadisticas)) {

    $estadisticas = [

        [
            "nombre" => "Usuarios registrados",
            "valor" => "1,248",
            "icono" => "◉",
            "color" => "purple"
        ],

        [
            "nombre" => "Cursos publicados",
            "valor" => "186",
            "icono" => "▣",
            "color" => "blue"
        ],

        [
            "nombre" => "Cursos vendidos",
            "valor" => "3,842",
            "icono" => "✓",
            "color" => "green"
        ],

        [
            "nombre" => "Ingresos generados",
            "valor" => "$284,650",
            "icono" => "$",
            "color" => "orange"
        ]

    ];

}


// =====================================================
// REPORTES DE PRUEBA
// =====================================================

if (empty($reportes)) {

    $reportes = [

        [
            "id" => 1,
            "tipo" => "Ventas",
            "titulo" => "Ventas de cursos",
            "descripcion" => "Resumen de cursos vendidos durante el período seleccionado.",
            "cantidad" => "3,842",
            "periodo" => "Marzo 2026",
            "estado" => "Disponible"
        ],

        [
            "id" => 2,
            "tipo" => "Usuarios",
            "titulo" => "Registro de usuarios",
            "descripcion" => "Usuarios registrados y distribución según su rol.",
            "cantidad" => "1,248",
            "periodo" => "Marzo 2026",
            "estado" => "Disponible"
        ],

        [
            "id" => 3,
            "tipo" => "Cursos",
            "titulo" => "Cursos publicados",
            "descripcion" => "Cursos disponibles actualmente en la plataforma.",
            "cantidad" => "186",
            "periodo" => "Marzo 2026",
            "estado" => "Disponible"
        ],

        [
            "id" => 4,
            "tipo" => "Actividad",
            "titulo" => "Actividad de la plataforma",
            "descripcion" => "Resumen de las principales acciones realizadas.",
            "cantidad" => "8,492",
            "periodo" => "Marzo 2026",
            "estado" => "Disponible"
        ]

    ];

}


// =====================================================
// ACTIVIDAD DE PRUEBA
// =====================================================

if (empty($actividad)) {

    $actividad = [

        [
            "tipo" => "Nuevo usuario",
            "titulo" => "Laura Martínez se registró",
            "descripcion" => "Nuevo estudiante registrado en la plataforma.",
            "fecha" => "Hace 15 minutos",
            "icono" => "+",
            "color" => "blue"
        ],

        [
            "tipo" => "Nuevo curso",
            "titulo" => "Curso publicado",
            "descripcion" => "Carlos Ramírez publicó un nuevo curso.",
            "fecha" => "Hace 42 minutos",
            "icono" => "▣",
            "color" => "purple"
        ],

        [
            "tipo" => "Venta",
            "titulo" => "Nueva compra realizada",
            "descripcion" => "Se registró una nueva venta de curso.",
            "fecha" => "Hace 1 hora",
            "icono" => "$",
            "color" => "green"
        ],

        [
            "tipo" => "Reporte",
            "titulo" => "Reporte generado",
            "descripcion" => "Se generó un nuevo reporte de ventas.",
            "fecha" => "Hace 2 horas",
            "icono" => "▤",
            "color" => "orange"
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
        Reportes | Academia
    </title>


    <!-- CSS GENERAL -->

    <link
        rel="stylesheet"
        href="../css/Style.css"
    >


    <!-- CSS DEL TABLERO -->

    <link
        rel="stylesheet"
        href="../css/Tablero.css"
    >


    <!-- CSS DE REPORTES -->

    <link
        rel="stylesheet"
        href="../css/ConsultaReportes.css"
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
                href="Usuarios.php"
                class="sidebar__link"
            >

                <span>◉</span>

                Usuarios

            </a>


            <a
                href="ConsultaReportes.php"
                class="sidebar__link active"
            >

                <span>▤</span>

                Reportes

            </a>


            <a
                href="../Compartidas/ChatCurso.php"
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
                    placeholder="Buscar en reportes..."
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

        <div class="dashboard-content reports-page">


            <!-- =================================================
                 CABECERA
            ================================================== -->

            <section class="reports-heading">


                <div>

                    <span class="section-tag">
                        ADMINISTRACIÓN
                    </span>


                    <h1>
                        Consulta de reportes
                    </h1>


                    <p>
                        Analiza el estado y actividad general de la plataforma.
                    </p>

                </div>


                <button
                    type="button"
                    class="btn btn--primary"
                >

                    ↓ Exportar reporte

                </button>


            </section>



            <!-- =================================================
                 FILTROS
            ================================================== -->

            <section class="reports-filters">


                <div class="report-filter">


                    <label>
                        Tipo de reporte
                    </label>


                    <select>

                        <option>
                            Todos los reportes
                        </option>

                        <option>
                            Ventas
                        </option>

                        <option>
                            Usuarios
                        </option>

                        <option>
                            Cursos
                        </option>

                        <option>
                            Actividad
                        </option>

                    </select>


                </div>



                <div class="report-filter">


                    <label>
                        Período
                    </label>


                    <select>

                        <option>
                            Este mes
                        </option>

                        <option>
                            Último mes
                        </option>

                        <option>
                            Últimos 3 meses
                        </option>

                        <option>
                            Este año
                        </option>

                    </select>


                </div>



                <div class="report-filter">


                    <label>
                        Fecha inicial
                    </label>


                    <input
                        type="date"
                        value="2026-03-01"
                    >


                </div>



                <div class="report-filter">


                    <label>
                        Fecha final
                    </label>


                    <input
                        type="date"
                        value="2026-03-31"
                    >


                </div>



                <button
                    type="button"
                    class="btn btn--outline report-filter__button"
                >

                    Aplicar filtros

                </button>


            </section>



            <!-- =================================================
                 ESTADÍSTICAS
            ================================================== -->

            <section class="reports-summary">


                <?php foreach ($estadisticas as $estadistica): ?>


                    <div class="report-stat">


                        <div
                            class="
                                report-stat__icon
                                <?= htmlspecialchars(
                                    $estadistica["color"] ?? "purple"
                                ) ?>
                            "
                        >

                            <?= htmlspecialchars(
                                $estadistica["icono"] ?? "•"
                            ) ?>

                        </div>


                        <div>

                            <span>

                                <?= htmlspecialchars(
                                    $estadistica["nombre"]
                                ) ?>

                            </span>


                            <strong>

                                <?= htmlspecialchars(
                                    $estadistica["valor"]
                                ) ?>

                            </strong>

                        </div>


                    </div>


                <?php endforeach; ?>


            </section>



            <!-- =================================================
                 REPORTES DISPONIBLES
            ================================================== -->

            <section class="reports-panel">


                <div class="reports-panel__header">


                    <div>

                        <h2>
                            Reportes disponibles
                        </h2>


                        <p>
                            Consulta información detallada de la plataforma.
                        </p>

                    </div>


                    <span class="reports-date">

                        Marzo 2026

                    </span>


                </div>



                <div class="reports-grid">


                    <?php foreach ($reportes as $reporte): ?>


                        <article class="report-card">


                            <div class="report-card__top">


                                <span
                                    class="
                                        report-type
                                        <?= strtolower(
                                            htmlspecialchars(
                                                $reporte["tipo"]
                                            )
                                        ) ?>
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $reporte["tipo"]
                                    ) ?>

                                </span>


                                <button
                                    type="button"
                                    class="report-more"
                                    aria-label="Más opciones"
                                >
                                    ⋮
                                </button>


                            </div>


                            <h3>

                                <?= htmlspecialchars(
                                    $reporte["titulo"]
                                ) ?>

                            </h3>


                            <p>

                                <?= htmlspecialchars(
                                    $reporte["descripcion"]
                                ) ?>

                            </p>


                            <div class="report-card__data">


                                <div>

                                    <span>
                                        Registros
                                    </span>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $reporte["cantidad"]
                                        ) ?>

                                    </strong>

                                </div>


                                <div>

                                    <span>
                                        Período
                                    </span>


                                    <strong>

                                        <?= htmlspecialchars(
                                            $reporte["periodo"]
                                        ) ?>

                                    </strong>

                                </div>


                            </div>


                            <div class="report-card__bottom">


                                <span class="report-available">

                                    <span></span>

                                    <?= htmlspecialchars(
                                        $reporte["estado"]
                                    ) ?>

                                </span>


                                <button
                                    type="button"
                                    class="report-view"
                                >

                                    Consultar →

                                </button>


                            </div>


                        </article>


                    <?php endforeach; ?>


                </div>


            </section>



            <!-- =================================================
                 ACTIVIDAD RECIENTE
            ================================================== -->

            <section class="reports-activity">


                <div class="reports-activity__header">


                    <div>

                        <h2>
                            Actividad reciente
                        </h2>


                        <p>
                            Últimos movimientos registrados.
                        </p>

                    </div>


                    <a href="#">
                        Ver toda la actividad
                    </a>

                </div>



                <div class="reports-activity__list">


                    <?php foreach ($actividad as $item): ?>


                        <div class="report-activity">


                            <div
                                class="
                                    report-activity__icon
                                    <?= htmlspecialchars(
                                        $item["color"] ?? "purple"
                                    ) ?>
                                "
                            >

                                <?= htmlspecialchars(
                                    $item["icono"] ?? "•"
                                ) ?>

                            </div>


                            <div class="report-activity__content">


                                <span>

                                    <?= htmlspecialchars(
                                        $item["tipo"]
                                    ) ?>

                                </span>


                                <strong>

                                    <?= htmlspecialchars(
                                        $item["titulo"]
                                    ) ?>

                                </strong>


                                <p>

                                    <?= htmlspecialchars(
                                        $item["descripcion"]
                                    ) ?>

                                </p>


                            </div>


                            <time>

                                <?= htmlspecialchars(
                                    $item["fecha"] ?? ""
                                ) ?>

                            </time>


                        </div>


                    <?php endforeach; ?>


                </div>


            </section>


        </div>


    </main>


</div>



<script src="../js/Main.js"></script>


</body>

</html>
