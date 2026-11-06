<?php
require_once __DIR__ . '/../../config.php';
$usuario = $datos['usuario'] ?? [];

$nombre = $usuario['nombre'] ?? '';
$apellido = $usuario['apellido'] ?? '';
$rol = $usuario['rol'] ?? '';

$inicial = strtoupper(
    substr($nombre, 0, 1)
);

$usuarios = $usuarios ?? [];

$totalUsuarios = $totalUsuarios ?? 0;
$usuariosActivos = $usuariosActivos ?? 0;
$usuariosPendientes = $usuariosPendientes ?? 0;
$usuariosBaneados = $usuariosBaneados ?? 0;
$estudiantes = $estudiantes ?? 0;
$instructores = $instructores ?? 0;

$csrfToken = $_SESSION['csrf_usuarios'] ?? '';

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
        Usuarios | Academia
    </title>

    <!-- CSS GENERAL -->
    <link rel="stylesheet"
        href="<?= url('View/css/Style.css') ?>">
    <link rel="stylesheet"
        href="<?= url('View/css/Tablero.css') ?>">
    <link rel="stylesheet"
        href="<?= url('View/css/Usuarios.css') ?>">
</head>

<body class="dashboard-body">
<div class="dashboard">
    <!-- =====================================================
         SIDEBAR
    ====================================================== -->
    <?php require_once __DIR__ . '/../Compartidas/SideBar.php'; ?>

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
                    placeholder="Buscar usuarios..."
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
        <div class="dashboard-content users-page">

            <!-- =================================================
                 CABECERA
            ================================================== -->
            <section class="users-heading">
                <div>
                    <span class="section-tag">
                        ADMINISTRACIÓN
                    </span>

                    <h1>
                        Usuarios
                    </h1>

                    <p>
                        Gestiona los usuarios registrados en la plataforma.
                    </p>
                </div>
            </section>

            <!-- =================================================
                 ESTADÍSTICAS
            ================================================== -->
            <section class="users-summary">
                <div class="user-stat">
                    <div class="user-stat__icon purple">
                        ◉
                    </div>

                    <div>
                        <span>
                            Total usuarios
                        </span>

                        <strong>
                            <?= $totalUsuarios ?>
                        </strong>
                    </div>
                </div>

                <div class="user-stat">
                    <div class="user-stat__icon green">
                        ✓
                    </div>

                    <div>
                        <span>
                            Usuarios activos
                        </span>

                        <strong>
                            <?= $usuariosActivos ?>
                        </strong>
                    </div>
                </div>

                <div class="user-stat">
                    <div class="user-stat__icon blue">
                        ▣
                    </div>

                    <div>
                        <span>
                            Estudiantes
                        </span>

                        <strong>
                            <?= $estudiantes ?>
                        </strong>
                    </div>
                </div>

                <div class="user-stat">
                    <div class="user-stat__icon orange">
                        ★
                    </div>

                    <div>
                        <span>
                            Instructores
                        </span>

                        <strong>
                            <?= $instructores ?>
                        </strong>
                    </div>
                </div>

                <div class="user-stat">
                    <div class="user-stat__icon orange">
                        ◷
                    </div>

                    <div>
                        <span>Usuarios pendientes</span>

                        <strong>
                            <?= $usuariosPendientes ?>
                        </strong>
                    </div>
                </div>

                <div class="user-stat">
                    <div class="user-stat__icon purple">
                        !
                    </div>

                    <div>
                        <span>Usuarios baneados</span>

                        <strong>
                            <?= $usuariosBaneados ?>
                        </strong>
                    </div>
                </div>
            </section>

            <!-- =================================================
                 PANEL DE USUARIOS
            ================================================== -->
            <section class="users-panel">

                <!-- CABECERA -->
                <div class="users-panel__header">
                    <div>
                        <h2>
                            Usuarios registrados
                        </h2>

                        <p>
                            Administra las cuentas de la plataforma.
                        </p>
                    </div>
                </div>

                <!-- FILTROS -->
                <div class="users-toolbar">

                    <form
                        method="GET"
                        action="<?= htmlspecialchars(url('Controller/UsuariosController.php')) ?>"
                        class="users-toolbar"
                    >

                        <div class="users-search">

                            <span>🔍</span>

                            <input
                                type="search"
                                name="buscar"
                                placeholder="Buscar por nombre o correo..."
                                value="<?= htmlspecialchars($buscar ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            >

                        </div>

                        <div class="users-filters">

                            <!-- FILTRO POR ROL -->

                            <select name="rol">

                                <option value="">Todos los roles</option>

                                <option
                                    value="Estudiante"
                                    <?= ($rolFiltro ?? '') === 'Estudiante' ? 'selected' : '' ?>
                                >
                                    Estudiantes
                                </option>

                                <option
                                    value="Instructor"
                                    <?= ($rolFiltro ?? '') === 'Instructor' ? 'selected' : '' ?>
                                >
                                    Instructores
                                </option>

                                <option
                                    value="Administrador"
                                    <?= ($rolFiltro ?? '') === 'Administrador' ? 'selected' : '' ?>
                                >
                                    Administradores
                                </option>

                            </select>


                            <!-- FILTRO POR ESTATUS -->

                            <select name="estatus">

                                <option value="">Todos los estados</option>

                                <option
                                    value="Pendiente"
                                    <?= ($estatusFiltro ?? '') === 'Pendiente' ? 'selected' : '' ?>
                                >
                                    Pendientes
                                </option>

                                <option
                                    value="Activo"
                                    <?= ($estatusFiltro ?? '') === 'Activo' ? 'selected' : '' ?>
                                >
                                    Activos
                                </option>

                                <option
                                    value="Baneado"
                                    <?= ($estatusFiltro ?? '') === 'Baneado' ? 'selected' : '' ?>
                                >
                                    Baneados
                                </option>

                            </select>


                            <button type="submit" class="btn btn--primary">
                                Filtrar
                            </button>


                            <a
                                href="<?= htmlspecialchars(url('Controller/UsuariosController.php')) ?>"
                                class="btn"
                            >
                                Limpiar
                            </a>

                        </div>

                    </form>

                </div>


                <!-- TABLA -->
                <div class="users-table-wrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>
                                    Usuario
                                </th>
                                <th>
                                    Rol
                                </th>
                                <th>
                                    Registro
                                </th>
                                <th>
                                    Estado
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($usuarios as $item): ?>
                                <tr>
                                    <!-- USUARIO -->
                                    <td>
                                        <div class="user-cell">
                                            <div>
                                                <strong>
                                                    <?= htmlspecialchars(
                                                        $item['nombre'] . ' ' . $item['apellidos']
                                                    ) ?>    
                                                </strong>

                                                <span>
                                                    <?= htmlspecialchars(
                                                        $item["email"]
                                                    ) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ROL -->
                                    <td>
                                        <span
                                            class="user-role">
                                            <?= htmlspecialchars(
                                                $item["nombre_rol"]
                                            ) ?>
                                        </span>
                                    </td>

                                    <!-- FECHA -->
                                    <td>
                                        <span class="user-date">
                                            <?= date(
                                                'd/m/Y',
                                                strtotime($item['fecha_registro'])
                                            ) ?>
                                        </span>
                                    </td>

                                    <!-- ESTADO -->
                                    <td>
                                        <?php
                                            $estadoClass = match ($item['estatus']) {
                                                'Activo' => 'active',

                                                'Pendiente' => 'pending',

                                                'Baneado' => 'inactive',

                                                default => 'inactive'
                                            };
                                        ?>

                                        <span class="user-status <?= $estadoClass ?>">
                                            <span></span>
                                            <?= htmlspecialchars($item['estatus']) ?>
                                        </span>
                                    </td>

                                    <!-- ACCIONES -->
                                    <td>
                                        <div class="user-actions">
                                            <!-- VER DETALLES -->
                                            <a
                                                href="?<?= http_build_query([
                                                    'rol' => $rolFiltro,
                                                    'estatus' => $estatusFiltro,
                                                    'buscar' => $buscar,
                                                    'detalle' => (int) $item['id_usuario']
                                                ]) ?>"
                                                class="user-action"
                                                title="Ver reporte del usuario"
                                            >
                                                👁
                                            </a>

                                            <!-- CAMBIAR ESTATUS -->
                                            <?php if (
                                                $item['estatus'] === 'Pendiente' ||
                                                $item['estatus'] === 'Baneado'
                                            ): ?>
                                                <form method="POST">
                                                    <input
                                                        type="hidden"
                                                        name="csrf_token"
                                                        value="<?= htmlspecialchars($csrfToken) ?>"
                                                    >
                                                    <input
                                                        type="hidden"
                                                        name="id_usuario"
                                                        value="<?= (int) $item['id_usuario'] ?>"
                                                    >
                                                    <input
                                                        type="hidden"
                                                        name="estatus"
                                                        value="Activo"
                                                    >
                                                    <button
                                                        type="submit"
                                                        class="user-action"
                                                        title="<?= $item['estatus'] === 'Pendiente'
                                                            ? 'Aprobar usuario'
                                                            : 'Reactivar usuario' ?>"
                                                        onclick="return confirm('¿Deseas activar esta cuenta?')"
                                                    >
                                                        ✓
                                                    </button>
                                                </form>
                                            <?php elseif ($item['estatus'] === 'Activo'): ?>
                                                <form method="POST">
                                                    <input
                                                        type="hidden"
                                                        name="csrf_token"
                                                        value="<?= htmlspecialchars($csrfToken) ?>">
                                                    <input
                                                        type="hidden"
                                                        name="id_usuario"
                                                        value="<?= (int) $item['id_usuario'] ?>">

                                                    <input
                                                        type="hidden"
                                                        name="estatus"
                                                        value="Baneado">

                                                    <button
                                                        type="submit"
                                                        class="user-action user-action--danger"
                                                        title="Suspender usuario"
                                                        onclick="return confirm('¿Deseas suspender esta cuenta?')"
                                                    >
                                                        ⊘
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (!empty($reporte)): ?>
                        <section class="users-panel user-detail-panel">
                            <div class="users-panel__header">
                                <div>
                                    <span class="section-tag">
                                        REPORTE INDIVIDUAL
                                    </span>

                                    <h2>
                                        <?= htmlspecialchars(
                                            $reporte['nombre'] . ' ' . $reporte['apellidos']
                                        ) ?>
                                    </h2>

                                    <p>
                                        <?= htmlspecialchars($reporte['email']) ?>
                                    </p>
                                </div>
                                <a
                                    href="<?= url('Controller/UsuariosController.php') ?>"
                                    class="btn"
                                >
                                    Cerrar reporte
                                </a>
                            </div>

                            <?php if (isset($reporte['total_cursos'])): ?>
                                <div class="users-summary">
                                    <?php if (isset($reporte['total_estudiantes'])): ?>
                                        <!-- INSTRUCTOR -->
                                        <div class="user-stat">
                                            <div>
                                                <span>Cursos creados</span>
                                                <strong>
                                                    <?= (int) $reporte['total_cursos'] ?>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="user-stat">
                                            <div>
                                                <span>Estudiantes inscritos</span>
                                                <strong>
                                                    <?= (int) $reporte['total_estudiantes'] ?>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="user-stat">
                                            <div>
                                                <span>Promedio de cursos</span>
                                                <strong>
                                                    <?= number_format(
                                                        (float) $reporte['promedio_cursos'],
                                                        2
                                                    ) ?>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="user-stat">
                                            <div>
                                                <span>Ingresos</span>
                                                <strong>
                                                    $<?= number_format(
                                                        (float) $reporte['ingresos'],
                                                        2
                                                    ) ?>
                                                </strong>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <!-- ESTUDIANTE -->
                                        <div class="user-stat">
                                            <div>
                                                <span>Cursos inscritos</span>
                                                <strong>
                                                    <?= (int) $reporte['total_cursos'] ?>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="user-stat">
                                            <div>
                                                <span>Cursos terminados</span>
                                                <strong>
                                                    <?= (int) $reporte['cursos_terminados'] ?>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="user-stat">
                                            <div>
                                                <span>Cursos activos</span>
                                                <strong>
                                                    <?= (int) $reporte['cursos_activos'] ?>
                                                </strong>
                                            </div>
                                        </div>

                                        <div class="user-stat">
                                            <div>
                                                <span>Certificados</span>
                                                <strong>
                                                    <?= (int) $reporte['total_certificados'] ?>
                                                </strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <h3>Detalle de cursos</h3>
                            <div class="users-table-wrapper">
                                <table class="users-table">
                                    <thead>
                                        <tr>
                                            <th>Curso</th>
                                            <?php if (isset($reporte['total_estudiantes'])): ?>
                                                <th>Estudiantes</th>
                                                <th>Promedio</th>
                                            <?php else: ?>
                                                <th>Instructor</th>
                                                <th>Progreso</th>
                                                <th>Estado</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($cursosReporte as $curso): ?>
                                            <tr>
                                                <td>
                                                    <?= htmlspecialchars(
                                                        $curso['nombre_curso']
                                                    ) ?>
                                                </td>

                                                <?php if (isset($reporte['total_estudiantes'])): ?>
                                                    <td>
                                                        <?= (int) $curso['total_estudiantes'] ?>
                                                    </td>

                                                    <td>
                                                        <?= number_format(
                                                            (float) $curso['promedio_general'],
                                                            2
                                                        ) ?>
                                                    </td>
                                                <?php else: ?>
                                                    <td>
                                                        <?= htmlspecialchars(
                                                            $curso['nombre_instructor'] . ' ' .
                                                            $curso['apellidos_instructor']
                                                        ) ?>
                                                    </td>

                                                    <td>
                                                        <?= number_format(
                                                            (float) $curso['progreso'],
                                                            2
                                                        ) ?>%
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($curso['estatus']) ?>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($cursosReporte)): ?>
                                            <tr>
                                                <td colspan="4">
                                                    No hay cursos registrados.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>

                

                <!-- PAGINACIÓN -->
                <div class="users-pagination">
                    <span>
                        Mostrando
                        <strong>
                            <?= $totalUsuarios ?>
                        </strong>
                        usuarios
                    </span>

                    <div>
                        <button
                            type="button"
                            disabled>
                            ‹
                        </button>

                        <button
                            type="button"
                            class="active"
                        >
                            1
                        </button>

                        <button
                            type="button"
                        >
                            2
                        </button>

                        <button
                            type="button"
                        >
                            3
                        </button>

                        <button
                            type="button"
                        >
                            ›
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script src="<?= url('View/js/Main.js') ?>"></script>
</body>

</html>
