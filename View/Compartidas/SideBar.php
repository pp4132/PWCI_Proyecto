<?php require_once __DIR__ . '/../../config.php';?>
<aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar__brand">
        <a
            href="<?= htmlspecialchars(url('View/Public/Index.php'), ENT_QUOTES, 'UTF-8') ?>"
            class="sidebar__logo"
        >
            ACADEMIA
        </a>
    </div>

    <!-- INFORMACIÓN DEL USUARIO -->
    <div class="sidebar__profile">
        <div class="sidebar__avatar">
            <?= htmlspecialchars($inicial, ENT_QUOTES, 'UTF-8') ?>
        </div>

        <div>
            <strong>
                <?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>
                <?= htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8') ?>
            </strong>

            <span>
                <?= htmlspecialchars($rol, ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>
    </div>

    <!-- NAVEGACIÓN -->
    <nav class="sidebar__nav">

        <span class="sidebar__label">
            PRINCIPAL
        </span>

        <!-- TABLERO -->
        <a
            href="<?= htmlspecialchars(url('Controller/TableroController.php'), ENT_QUOTES, 'UTF-8') ?>"
            class="sidebar__link"
        >
            <span>▦</span>
            Tablero
        </a>

        <!-- OPCIONES DEL ESTUDIANTE -->
        <?php if ($rol === 'Estudiante'): ?>
            <a href="<?= htmlspecialchars( url('Controller/CursosController.php'),
                ENT_QUOTES, 'UTF-8') ?>" class="sidebar__link"><span>▣</span>Mis Cursos
            </a>
            <a href="<?= htmlspecialchars( url('Controller/KardexController.php'),
                ENT_QUOTES, 'UTF-8') ?>" class="sidebar__link"><span>⚙</span>Kardex
            </a>
        <?php endif; ?>

        <!-- OPCIONES DEL INSTRUCTOR -->
        <?php if ($rol === 'Instructor'): ?>
            <a
                href="<?= htmlspecialchars(url('Controller/MisCursosController.php'), ENT_QUOTES, 'UTF-8') ?>"
                class="sidebar__link"
            >
                <span>▣</span>
                Mis cursos
            </a>
            <a
                href="<?= htmlspecialchars(url('Controller/CrearCursosController.php'), ENT_QUOTES, 'UTF-8') ?>"
                class="sidebar__link"
            >
                <span>＋</span>
                Crear curso
            </a>
            <a
                href="<?= htmlspecialchars(url('Controller/VentasController.php'), ENT_QUOTES, 'UTF-8') ?>"
                class="sidebar__link"
            >
                <span>$</span>
                Ventas
            </a>
        <?php endif; ?>

        <!-- OPCIONES DEL ADMINISTRADOR -->
        <?php if ($rol === 'Administrador'): ?>
            <a
                href="<?= htmlspecialchars(url('Controller/UsuariosController.php'), ENT_QUOTES, 'UTF-8') ?>"
                class="sidebar__link"
            >
                <span>◉</span>
                Usuarios
            </a>
            <a
                href="<?= htmlspecialchars(url('Controller/ConsultaRepController.php'), ENT_QUOTES, 'UTF-8') ?>"
                class="sidebar__link"
            >
                <span>▤</span>
                Reportes
            </a>
            <!-- Ajusta este enlace cuando definas
                 la pantalla de chat del administrador. -->
        <?php endif; ?>
        <span class="sidebar__label">
            CUENTA
        </span>
        <!-- PERFIL -->
        <a href="<?= htmlspecialchars( url('Controller/PerfilController.php'),
            ENT_QUOTES, 'UTF-8') ?>" class="sidebar__link"><span>⚙</span>Mi perfil</a>
    </nav>
    <!-- CERRAR SESIÓN -->
    <div class="sidebar__bottom">
        <a
            href="<?= htmlspecialchars(url('Controller/LogoutController.php'), ENT_QUOTES, 'UTF-8') ?>"
            class="sidebar__logout"
        >
            <span>←</span>
            Cerrar sesión
        </a>
    </div>
</aside>