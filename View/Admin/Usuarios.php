<?php

/*
|--------------------------------------------------------------------------
| GESTIÓN DE USUARIOS
|--------------------------------------------------------------------------
|
| Esta vista recibe toda la información desde el Controller.
|
| Datos esperados:
|
| $datos["usuario"]
| $datos["usuarios"]
|
|--------------------------------------------------------------------------
*/


$usuario = $datos["usuario"] ?? [];

$usuarios = $datos["usuarios"] ?? [];


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

if (empty($usuarios)) {

    $usuarios = [

        [
            "id" => 1,
            "nombre" => "Carlos Ramírez",
            "email" => "carlos@example.com",
            "rol" => "Instructor",
            "fecha" => "12/01/2026",
            "estado" => "Activo"
        ],

        [
            "id" => 2,
            "nombre" => "Laura Martínez",
            "email" => "laura@example.com",
            "rol" => "Estudiante",
            "fecha" => "18/01/2026",
            "estado" => "Activo"
        ],

        [
            "id" => 3,
            "nombre" => "Sofía Hernández",
            "email" => "sofia@example.com",
            "rol" => "Instructor",
            "fecha" => "25/01/2026",
            "estado" => "Activo"
        ],

        [
            "id" => 4,
            "nombre" => "Miguel Torres",
            "email" => "miguel@example.com",
            "rol" => "Estudiante",
            "fecha" => "02/02/2026",
            "estado" => "Activo"
        ],

        [
            "id" => 5,
            "nombre" => "Daniel García",
            "email" => "daniel@example.com",
            "rol" => "Estudiante",
            "fecha" => "08/02/2026",
            "estado" => "Inactivo"
        ],

        [
            "id" => 6,
            "nombre" => "Ana López",
            "email" => "ana@example.com",
            "rol" => "Instructor",
            "fecha" => "15/02/2026",
            "estado" => "Activo"
        ],

        [
            "id" => 7,
            "nombre" => "Pedro Sánchez",
            "email" => "pedro@example.com",
            "rol" => "Estudiante",
            "fecha" => "21/02/2026",
            "estado" => "Activo"
        ],

        [
            "id" => 8,
            "nombre" => "Administrador Principal",
            "email" => "admin@example.com",
            "rol" => "Administrador",
            "fecha" => "01/01/2026",
            "estado" => "Activo"
        ]

    ];

}


// =====================================================
// ESTADÍSTICAS
// =====================================================

$totalUsuarios = count($usuarios);

$usuariosActivos = 0;

$estudiantes = 0;

$instructores = 0;


foreach ($usuarios as $item) {

    if (($item["estado"] ?? "") === "Activo") {

        $usuariosActivos++;

    }


    if (($item["rol"] ?? "") === "Estudiante") {

        $estudiantes++;

    }


    if (($item["rol"] ?? "") === "Instructor") {

        $instructores++;

    }

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
        Usuarios | Academia
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


    <!-- CSS DE USUARIOS -->

    <link
        rel="stylesheet"
        href="../css/Usuarios.css"
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
                class="sidebar__link active"
            >

                <span>◉</span>

                Usuarios

            </a>


            <a
                href="ConsultaReportes.php"
                class="sidebar__link"
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


                    <button
                        type="button"
                        class="btn btn--primary"
                    >

                        + Nuevo usuario

                    </button>


                </div>



                <!-- FILTROS -->

                <div class="users-toolbar">


                    <div class="users-search">


                        <span>
                            🔍
                        </span>


                        <input
                            type="search"
                            placeholder="Buscar por nombre o correo..."
                        >

                    </div>



                    <div class="users-filters">


                        <select
                            aria-label="Filtrar por rol"
                        >

                            <option value="todos">
                                Todos los roles
                            </option>

                            <option value="estudiante">
                                Estudiantes
                            </option>

                            <option value="instructor">
                                Instructores
                            </option>

                            <option value="administrador">
                                Administradores
                            </option>

                        </select>


                        <select
                            aria-label="Filtrar por estado"
                        >

                            <option value="todos">
                                Todos los estados
                            </option>

                            <option value="activo">
                                Activos
                            </option>

                            <option value="inactivo">
                                Inactivos
                            </option>

                        </select>


                    </div>


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


                                <?php

                                $nombreUsuario =
                                    $item["nombre"] ?? "Usuario";

                                $inicialUsuario =
                                    strtoupper(
                                        substr(
                                            $nombreUsuario,
                                            0,
                                            1
                                        )
                                    );

                                $estado =
                                    $item["estado"] ?? "Activo";

                                $rolUsuario =
                                    $item["rol"] ?? "Estudiante";

                                ?>


                                <tr>


                                    <!-- USUARIO -->

                                    <td>


                                        <div class="user-cell">


                                            <div class="user-cell__avatar">

                                                <?= htmlspecialchars(
                                                    $inicialUsuario
                                                ) ?>

                                            </div>


                                            <div>

                                                <strong>

                                                    <?= htmlspecialchars(
                                                        $nombreUsuario
                                                    ) ?>

                                                </strong>


                                                <span>

                                                    <?= htmlspecialchars(
                                                        $item["email"] ?? ""
                                                    ) ?>

                                                </span>

                                            </div>


                                        </div>


                                    </td>



                                    <!-- ROL -->

                                    <td>


                                        <?php

                                        $rolClass = match ($rolUsuario) {

                                            "Instructor" =>
                                                "instructor",

                                            "Administrador" =>
                                                "administrator",

                                            default =>
                                                "student"

                                        };

                                        ?>


                                        <span
                                            class="
                                                user-role
                                                <?= $rolClass ?>
                                            "
                                        >

                                            <?= htmlspecialchars(
                                                $rolUsuario
                                            ) ?>

                                        </span>


                                    </td>



                                    <!-- FECHA -->

                                    <td>

                                        <span class="user-date">

                                            <?= htmlspecialchars(
                                                $item["fecha"] ?? ""
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- ESTADO -->

                                    <td>


                                        <span
                                            class="
                                                user-status
                                                <?= $estado === "Activo"
                                                    ? "active"
                                                    : "inactive" ?>
                                            "
                                        >

                                            <span></span>

                                            <?= htmlspecialchars(
                                                $estado
                                            ) ?>

                                        </span>


                                    </td>



                                    <!-- ACCIONES -->

                                    <td>


                                        <div class="user-actions">


                                            <a
                                                href="#"
                                                class="user-action"
                                                title="Ver usuario"
                                            >
                                                👁
                                            </a>


                                            <a
                                                href="#"
                                                class="user-action"
                                                title="Editar usuario"
                                            >
                                                ✎
                                            </a>


                                            <a
                                                href="#"
                                                class="
                                                    user-action
                                                    user-action--danger
                                                "
                                                title="Desactivar usuario"
                                            >
                                                ⋮
                                            </a>


                                        </div>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>


                    </table>


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
                            disabled
                        >
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



<script src="../js/Main.js"></script>


</body>

</html>
