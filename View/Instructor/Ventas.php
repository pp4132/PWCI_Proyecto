<?php

/*
|--------------------------------------------------------------------------
| VENTAS - INSTRUCTOR
|--------------------------------------------------------------------------
|
| Vista visual de las ventas del instructor.
|
| Por ahora se utilizan datos de ejemplo.
| Posteriormente la información será proporcionada por el Controller.
|
|--------------------------------------------------------------------------
*/


$nombre = "Carlos";
$apellido = "Ramírez";
$rol = "Instructor";

$inicial = strtoupper(
    substr($nombre, 0, 1)
);


// =====================================================
// RESUMEN DE VENTAS
// =====================================================

$resumen = [

    [
        "titulo" => "Ingresos totales",
        "valor" => "$12,480",
        "descripcion" => "+18.4% este mes",
        "icono" => "$",
        "color" => "green"
    ],

    [
        "titulo" => "Ventas",
        "valor" => "318",
        "descripcion" => "+12.6% este mes",
        "icono" => "↗",
        "color" => "blue"
    ],

    [
        "titulo" => "Ingresos este mes",
        "valor" => "$2,840",
        "descripcion" => "+8.2% respecto al anterior",
        "icono" => "◷",
        "color" => "purple"
    ],

    [
        "titulo" => "Ticket promedio",
        "valor" => "$39.25",
        "descripcion" => "Por venta realizada",
        "icono" => "◎",
        "color" => "orange"
    ]

];


// =====================================================
// VENTAS RECIENTES
// =====================================================

$ventas = [

    [
        "curso" => "PHP desde cero",
        "estudiante" => "Ana López",
        "fecha" => "Hoy, 10:42",
        "importe" => "$499",
        "estado" => "Completada",
        "inicial" => "A"
    ],

    [
        "curso" => "JavaScript moderno",
        "estudiante" => "Miguel Torres",
        "fecha" => "Hoy, 09:18",
        "importe" => "$599",
        "estado" => "Completada",
        "inicial" => "M"
    ],

    [
        "curso" => "PHP desde cero",
        "estudiante" => "Sofía Hernández",
        "fecha" => "Ayer, 18:35",
        "importe" => "$499",
        "estado" => "Completada",
        "inicial" => "S"
    ],

    [
        "curso" => "Bases de datos con MySQL",
        "estudiante" => "Daniel García",
        "fecha" => "Ayer, 15:20",
        "importe" => "$399",
        "estado" => "Completada",
        "inicial" => "D"
    ],

    [
        "curso" => "Diseño de interfaces UI/UX",
        "estudiante" => "Laura Martínez",
        "fecha" => "12 Sep, 13:05",
        "importe" => "$449",
        "estado" => "Completada",
        "inicial" => "L"
    ]

];


// =====================================================
// CURSOS CON MÁS VENTAS
// =====================================================

$rankingCursos = [

    [
        "posicion" => 1,
        "titulo" => "PHP desde cero",
        "ventas" => 125,
        "ingresos" => "$6,237",
        "color" => "purple"
    ],

    [
        "posicion" => 2,
        "titulo" => "JavaScript moderno",
        "ventas" => 84,
        "ingresos" => "$5,124",
        "color" => "blue"
    ],

    [
        "posicion" => 3,
        "titulo" => "Bases de datos con MySQL",
        "ventas" => 67,
        "ingresos" => "$3,333",
        "color" => "green"
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
        Ventas | Academia
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


    <!-- CSS DE VENTAS -->

    <link
        rel="stylesheet"
        href="../css/Ventas.css"
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
                class="sidebar__link"
            >

                <span>＋</span>

                Crear curso

            </a>


            <a
                href="Ventas.php"
                class="sidebar__link active"
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

        <div class="dashboard-content sales-page">


            <!-- ENCABEZADO -->

            <section class="sales-header">


                <div>

                    <span class="section-tag">
                        INSTRUCTOR
                    </span>


                    <h1>
                        Ventas
                    </h1>


                    <p>
                        Consulta tus ingresos y el rendimiento
                        de tus cursos.
                    </p>

                </div>


                <button
                    type="button"
                    class="btn btn--outline"
                >

                    ↓ Exportar

                </button>


            </section>



            <!-- =================================================
                 RESUMEN
            ================================================== -->

            <section class="sales-summary">


                <?php foreach (
                    $resumen as $item
                ): ?>


                    <article class="sales-stat">


                        <div
                            class="
                                sales-stat__icon
                                <?= htmlspecialchars(
                                    $item["color"]
                                ) ?>
                            "
                        >

                            <?= htmlspecialchars(
                                $item["icono"]
                            ) ?>

                        </div>


                        <div class="sales-stat__content">


                            <span>

                                <?= htmlspecialchars(
                                    $item["titulo"]
                                ) ?>

                            </span>


                            <strong>

                                <?= htmlspecialchars(
                                    $item["valor"]
                                ) ?>

                            </strong>


                            <small>

                                <?= htmlspecialchars(
                                    $item["descripcion"]
                                ) ?>

                            </small>


                        </div>


                    </article>


                <?php endforeach; ?>


            </section>



            <!-- =================================================
                 GRÁFICA + RANKING
            ================================================== -->

            <section class="sales-overview">


                <!-- GRÁFICA -->

                <article class="sales-panel sales-chart-panel">


                    <div class="sales-panel__header">


                        <div>

                            <h2>
                                Ingresos
                            </h2>


                            <p>
                                Evolución de tus ingresos durante el año.
                            </p>

                        </div>


                        <select
                            class="sales-select"
                        >

                            <option>
                                Últimos 12 meses
                            </option>

                            <option>
                                Últimos 6 meses
                            </option>

                            <option>
                                Este año
                            </option>

                        </select>


                    </div>


                    <!-- GRÁFICA VISUAL -->

                    <div class="chart">


                        <div class="chart__y-axis">

                            <span>
                                $3k
                            </span>

                            <span>
                                $2k
                            </span>

                            <span>
                                $1k
                            </span>

                            <span>
                                $0
                            </span>

                        </div>


                        <div class="chart__area">


                            <div class="chart__lines">

                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>

                            </div>


                            <div class="chart__bars">


                                <div
                                    class="chart-bar"
                                    style="height: 35%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 48%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 42%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 63%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 55%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 72%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 68%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 82%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 75%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 91%;"
                                ></div>


                                <div
                                    class="chart-bar"
                                    style="height: 84%;"
                                ></div>


                                <div
                                    class="chart-bar chart-bar--current"
                                    style="height: 96%;"
                                ></div>


                            </div>


                            <div class="chart__months">

                                <span>Ene</span>
                                <span>Feb</span>
                                <span>Mar</span>
                                <span>Abr</span>
                                <span>May</span>
                                <span>Jun</span>
                                <span>Jul</span>
                                <span>Ago</span>
                                <span>Sep</span>
                                <span>Oct</span>
                                <span>Nov</span>
                                <span>Dic</span>

                            </div>


                        </div>

                    </div>


                </article>



                <!-- CURSOS MÁS VENDIDOS -->

                <article class="sales-panel">


                    <div class="sales-panel__header">


                        <div>

                            <h2>
                                Cursos con más ventas
                            </h2>


                            <p>
                                Rendimiento de tus cursos.
                            </p>

                        </div>


                    </div>


                    <div class="top-courses">


                        <?php foreach (
                            $rankingCursos as $curso
                        ): ?>


                            <div class="top-course">


                                <div
                                    class="
                                        top-course__number
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $curso["posicion"]
                                    ) ?>

                                </div>


                                <div
                                    class="
                                        top-course__image
                                        <?= htmlspecialchars(
                                            $curso["color"]
                                        ) ?>
                                    "
                                >
                                    A
                                </div>


                                <div
                                    class="
                                        top-course__info
                                    "
                                >

                                    <strong>

                                        <?= htmlspecialchars(
                                            $curso["titulo"]
                                        ) ?>

                                    </strong>


                                    <span>

                                        <?= htmlspecialchars(
                                            $curso["ventas"]
                                        ) ?>

                                        ventas

                                    </span>

                                </div>


                                <strong
                                    class="
                                        top-course__income
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $curso["ingresos"]
                                    ) ?>

                                </strong>


                            </div>


                        <?php endforeach; ?>


                    </div>


                </article>


            </section>



            <!-- =================================================
                 VENTAS RECIENTES
            ================================================== -->

            <section class="sales-panel recent-sales">


                <div class="sales-panel__header">


                    <div>

                        <h2>
                            Ventas recientes
                        </h2>


                        <p>
                            Últimas compras realizadas por estudiantes.
                        </p>

                    </div>


                    <select
                        class="sales-select"
                    >

                        <option>
                            Todas las ventas
                        </option>

                        <option>
                            Este mes
                        </option>

                        <option>
                            Últimos 30 días
                        </option>

                    </select>


                </div>



                <div class="sales-table-wrapper">


                    <table class="sales-table">


                        <thead>

                            <tr>

                                <th>
                                    Estudiante
                                </th>

                                <th>
                                    Curso
                                </th>

                                <th>
                                    Fecha
                                </th>

                                <th>
                                    Importe
                                </th>

                                <th>
                                    Estado
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php foreach (
                                $ventas as $venta
                            ): ?>


                                <tr>


                                    <td>


                                        <div
                                            class="
                                                sale-student
                                            "
                                        >


                                            <div
                                                class="
                                                    sale-student__avatar
                                                "
                                            >

                                                <?= htmlspecialchars(
                                                    $venta["inicial"]
                                                ) ?>

                                            </div>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $venta["estudiante"]
                                                ) ?>

                                            </strong>


                                        </div>


                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $venta["curso"]
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $venta["fecha"]
                                        ) ?>

                                    </td>


                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $venta["importe"]
                                            ) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <span
                                            class="
                                                sale-status
                                                completed
                                            "
                                        >

                                            <span></span>

                                            <?= htmlspecialchars(
                                                $venta["estado"]
                                            ) ?>

                                        </span>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>


                    </table>


                </div>


            </section>


        </div>


    </main>


</div>



<script src="../js/Main.js"></script>


</body>

</html>
