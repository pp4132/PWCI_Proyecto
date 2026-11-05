
<?php

require_once __DIR__ . '/../../config.php';

// =====================================================
// DATOS DEL INSTRUCTOR
// =====================================================

$usuario = $datos['usuario'] ?? [];

$nombre = $usuario['nombre'] ?? '';
$apellido = $usuario['apellido'] ?? '';
$correo = $usuario['correo'] ?? '';
$telefono = $usuario['telefono'] ?? '';
$rol = $usuario['rol'] ?? '';

$inicial = strtoupper(
    substr($nombre, 0, 1)
);

// =====================================================
// INFORMACIÓN DE VENTAS
// =====================================================

$resumen = $datos['resumen'] ?? [];

$ventas = $datos['ventas'] ?? [];

$rankingCursos = $datos['rankingCursos'] ?? [];

$grafica = $datos['grafica'] ?? [];

// =====================================================
// CONFIGURACIÓN DE GRÁFICA
// =====================================================

$maxIngresos = 0;

foreach ($grafica as $mes) {

    $maxIngresos = max(
        $maxIngresos,
        (float) $mes['ingresos']
    );

}

// Evitar división entre cero.

$maxIngresos = max($maxIngresos, 1);

// =====================================================
// FUNCIONES DE FORMATO
// =====================================================

function formatoDinero($cantidad): string
{
    return '$' . number_format(
        (float) $cantidad,
        2
    );
}

function estadoPagoTexto(string $estado): string
{
    return match ($estado) {

        'Completado' => 'Completada',

        'Pendiente' => 'Pendiente',

        'Fallido' => 'Fallida',

        'Reembolsado' => 'Reembolsada',

        default => 'Desconocido'

    };
}

function estadoPagoClase(string $estado): string
{
    return match ($estado) {

        'Completado' => 'completed',

        'Pendiente' => 'pending',

        'Fallido' => 'failed',

        'Reembolsado' => 'refunded',

        default => ''

    };
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
        Ventas | Academia
    </title>


    <!-- CSS GENERAL -->
     <link rel="stylesheet"
        href="<?= url('View/css/Style.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/Tablero.css') ?>">

    <link rel="stylesheet"
        href="<?= url('View/css/Ventas.css') ?>">

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
                                <?= formatoDinero($maxIngresos) ?>
                            </span>

                            <span>
                                <?= formatoDinero($maxIngresos * 0.66) ?>
                            </span>

                            <span>
                                <?= formatoDinero($maxIngresos * 0.33) ?>
                            </span>

                            <span>
                                $0.00
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

                                <?php foreach ($grafica as $mes): ?>

                                    <?php

                                    $ingreso = (float) $mes['ingresos'];

                                    $altura = ($ingreso / $maxIngresos) * 100;

                                    ?>

                                    <div
                                        class="chart-bar <?= $mes['periodo'] === date('Y-m') ? 'chart-bar--current' : '' ?>"
                                        style="height: <?= $altura ?>%;"
                                        title="<?= htmlspecialchars($mes['mes']) ?>: <?= formatoDinero($ingreso) ?>"
                                    ></div>

                                <?php endforeach; ?>

                            </div>

                            <div class="chart__months">

                                <?php foreach ($grafica as $mes): ?>

                                    <span>
                                        <?= htmlspecialchars($mes['mes']) ?>
                                    </span>

                                <?php endforeach; ?>

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
                                        <?= htmlspecialchars($curso['color']) ?>
                                    "
                                >

                                    <?php if (!empty($curso['imagen_url'])): ?>

                                        <img
                                            src="<?= htmlspecialchars($curso['imagen_url']) ?>"
                                            alt="<?= htmlspecialchars($curso['titulo']) ?>"
                                            style="width:100%;height:100%;object-fit:cover;"
                                        >

                                    <?php else: ?>

                                        A

                                    <?php endif; ?>

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

                                <strong class="top-course__income">

                                    <?= formatoDinero($curso['ingresos']) ?>

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

                    <select class="sales-select">
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
                            <?php if (!empty($ventas)): ?>
                                <?php foreach ($ventas as $venta): ?>

                                    <tr>
                                        <td>
                                            <div class="sale-student">
                                                <div class="sale-student__avatar">
                                                    <?= htmlspecialchars(
                                                        $venta["inicial"]
                                                    ) ?>
                                                </div>

                                                <strong>
                                                    <?= htmlspecialchars($venta['estudiante']) ?>
                                                </strong>
                                
                                            </div>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($venta['curso']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($venta['fecha']) ?>
                                        </td>

                                        <td>
                                            <strong>
                                                <?= formatoDinero($venta['importe']) ?>
                                            </strong>
                                        </td>

                                        <td>

                                            <?php

                                            $claseEstado = estadoPagoClase(
                                                $venta['estado']
                                            );

                                            $textoEstado = estadoPagoTexto(
                                                $venta['estado']
                                            );

                                            ?>

                                            <span class="sale-status <?= htmlspecialchars($claseEstado) ?>">

                                                <span></span>

                                                <?= htmlspecialchars($textoEstado) ?>

                                            </span>

                                        </td>



                                    </tr>


                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:30px;">
                                        Todavía no tienes ventas registradas.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                </div>

            </section>

        </div>
    </main>
</div>

<script src="<?= url('View/js/Main.js') ?>"></script>
</body>

</html>
