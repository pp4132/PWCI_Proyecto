<?php
require_once __DIR__ . '/../../config.php';

$usuario = $datos['usuario'] ?? [];

$nombre = $usuario['nombre'] ?? '';
$apellido = $usuario['apellido'] ?? '';
$correo = $usuario['correo'] ?? '';
$telefono = $usuario['telefono'] ?? '';
$rol = $usuario['rol'] ?? '';

$reportes = $datos['reportes'] ?? [];
$estadisticas = $datos['estadisticas'] ?? [];
$actividad = $datos['actividad'] ?? [];

$filtros = $datos['filtros'] ?? [];

$fechaInicio = $filtros['fecha_inicio'] ?? '';
$fechaFin = $filtros['fecha_fin'] ?? '';

$inicial = strtoupper(
    substr($nombre, 0, 1)
);



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

     <link rel="stylesheet" href="<?= url('View/css/Style.css') ?>">

    <link rel="stylesheet" href="<?= url('View/css/Tablero.css') ?>">

    <link rel="stylesheet" href="<?= url('View/css/ConsultaReportes.css') ?>">
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
            <form method="GET" class="reports-filters">

                <div class="report-filter">

                    <label for="tipo">
                        Tipo de reporte
                    </label>

                    <select name="tipo" id="tipo">

                        <option value="todos">
                            Todos los reportes
                        </option>

                        <option value="ventas">
                            Ventas
                        </option>

                        <option value="usuarios">
                            Usuarios
                        </option>

                        <option value="cursos">
                            Cursos
                        </option>

                        <option value="actividad">
                            Actividad
                        </option>

                    </select>

                </div>

                <div class="report-filter">

                    <label for="fecha_inicio">
                        Fecha inicial
                    </label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        id="fecha_inicio"
                        value="<?= htmlspecialchars($fechaInicio) ?>"
                        required
                    >

                </div>

                <div class="report-filter">

                    <label for="fecha_fin">
                        Fecha final
                    </label>

                    <input
                        type="date"
                        name="fecha_fin"
                        id="fecha_fin"
                        value="<?= htmlspecialchars($fechaFin) ?>"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn--outline report-filter__button"
                >
                    Aplicar filtros
                </button>

            </form>

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
                        <?= htmlspecialchars(
                            $fechaInicio . ' - ' . $fechaFin
                        ) ?>
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
                    <?php if (!empty($actividad)): ?>

                        <?php foreach ($actividad as $item): ?>

                            <div class="report-activity">

                                <div class="
                                    report-activity__icon
                                    <?= htmlspecialchars($item['color']) ?>
                                ">

                                    <?= htmlspecialchars($item['icono']) ?>

                                </div>

                                <div class="report-activity__content">

                                    <span>
                                        <?= htmlspecialchars($item['tipo']) ?>
                                    </span>

                                    <strong>
                                        <?= htmlspecialchars($item['titulo']) ?>
                                    </strong>

                                    <p>
                                        <?= htmlspecialchars($item['descripcion']) ?>
                                    </p>

                                </div>

                                <time>
                                    <?= htmlspecialchars($item['fecha']) ?>
                                </time>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <p class="empty-message">
                            No hay actividad registrada todavía.
                        </p>

                    <?php endif; ?>
                </div>

            </section>

        </div>
    </main>

</div>

<script src="<?= url('View/js/Main.js') ?>"></script>
</body>

</html>
