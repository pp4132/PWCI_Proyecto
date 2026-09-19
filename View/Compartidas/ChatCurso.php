<?php

/*
|--------------------------------------------------------------------------
| CHAT DE CURSO - VISTA COMPARTIDA
|--------------------------------------------------------------------------
|
| Esta vista puede ser utilizada por:
|
| - Estudiante
| - Instructor
| - Administrador
|
| El Controller determinará posteriormente qué conversaciones puede
| consultar cada tipo de usuario.
|
| Por ahora se utilizan datos de ejemplo para visualizar el diseño.
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
// CONVERSACIONES
// =====================================================

$conversaciones = [

    [
        "id" => 1,
        "nombre" => "Ana López",
        "inicial" => "A",
        "curso" => "PHP desde cero",
        "mensaje" => "¿Podrías explicar nuevamente el ejercicio?",
        "hora" => "10:42",
        "no_leidos" => 2,
        "color" => "purple"
    ],

    [
        "id" => 2,
        "nombre" => "Miguel Torres",
        "inicial" => "M",
        "curso" => "JavaScript moderno",
        "mensaje" => "Muchas gracias por la explicación.",
        "hora" => "09:18",
        "no_leidos" => 0,
        "color" => "blue"
    ],

    [
        "id" => 3,
        "nombre" => "Sofía Hernández",
        "inicial" => "S",
        "curso" => "PHP desde cero",
        "mensaje" => "Ya pude completar la actividad.",
        "hora" => "Ayer",
        "no_leidos" => 0,
        "color" => "orange"
    ],

    [
        "id" => 4,
        "nombre" => "Daniel García",
        "inicial" => "D",
        "curso" => "Bases de datos con MySQL",
        "mensaje" => "¿Cuándo estará disponible el siguiente módulo?",
        "hora" => "Ayer",
        "no_leidos" => 1,
        "color" => "green"
    ],

    [
        "id" => 5,
        "nombre" => "Laura Martínez",
        "inicial" => "L",
        "curso" => "Diseño de interfaces UI/UX",
        "mensaje" => "Tengo una duda sobre el proyecto final.",
        "hora" => "12 Sep",
        "no_leidos" => 0,
        "color" => "pink"
    ]

];


// =====================================================
// MENSAJES DE EJEMPLO
// =====================================================

$mensajes = [

    [
        "emisor" => "Ana López",
        "inicial" => "A",
        "mensaje" => "Hola Carlos, tengo una duda con el ejercicio de variables.",
        "hora" => "10:35",
        "propio" => false
    ],

    [
        "emisor" => "Carlos Ramírez",
        "inicial" => "C",
        "mensaje" => "¡Hola Ana! Claro, dime qué parte te está dando problemas.",
        "hora" => "10:37",
        "propio" => true
    ],

    [
        "emisor" => "Ana López",
        "inicial" => "A",
        "mensaje" => "No entiendo muy bien cuándo debería utilizar una variable de tipo string.",
        "hora" => "10:39",
        "propio" => false
    ],

    [
        "emisor" => "Carlos Ramírez",
        "inicial" => "C",
        "mensaje" => "Una variable string se utiliza cuando necesitas almacenar texto. Por ejemplo, el nombre de un usuario.",
        "hora" => "10:41",
        "propio" => true
    ],

    [
        "emisor" => "Ana López",
        "inicial" => "A",
        "mensaje" => "¿Podrías explicar nuevamente el ejercicio?",
        "hora" => "10:42",
        "propio" => false
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
        Chat | Academia
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


    <!-- CSS DEL CHAT -->

    <link
        rel="stylesheet"
        href="../css/ChatCurso.css"
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
                href="Tablero.php"
                class="sidebar__link"
            >

                <span>▦</span>

                Tablero

            </a>


            <?php if ($rol === "Instructor"): ?>


                <a
                    href="../Instructor/MisCursos.php"
                    class="sidebar__link"
                >

                    <span>▣</span>

                    Mis cursos

                </a>


                <a
                    href="../Instructor/CreacionCurso.php"
                    class="sidebar__link"
                >

                    <span>＋</span>

                    Crear curso

                </a>


                <a
                    href="../Instructor/Ventas.php"
                    class="sidebar__link"
                >

                    <span>$</span>

                    Ventas

                </a>


            <?php elseif ($rol === "Estudiante"): ?>


                <a
                    href="../Estudiante/Cursos.php"
                    class="sidebar__link"
                >

                    <span>▣</span>

                    Mis cursos

                </a>


                <a
                    href="../Estudiante/Kardex.php"
                    class="sidebar__link"
                >

                    <span>▤</span>

                    Kardex

                </a>


            <?php elseif ($rol === "Administrador"): ?>


                <a
                    href="../Admin/Usuarios.php"
                    class="sidebar__link"
                >

                    <span>◉</span>

                    Usuarios

                </a>


                <a
                    href="../Admin/ConsultaReportes.php"
                    class="sidebar__link"
                >

                    <span>▤</span>

                    Reportes

                </a>


            <?php endif; ?>


            <a
                href="ChatCurso.php"
                class="sidebar__link active"
            >

                <span>◌</span>

                Chat

            </a>


            <span class="sidebar__label">
                CUENTA
            </span>


            <a
                href="Perfil.php"
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
             CHAT
        ====================================================== -->

        <div class="chat-page">


            <!-- =================================================
                 LISTA DE CONVERSACIONES
            ================================================== -->

            <aside class="chat-sidebar">


                <div class="chat-sidebar__header">


                    <div>

                        <span class="section-tag">
                            COMUNICACIÓN
                        </span>


                        <h1>
                            Mensajes
                        </h1>

                    </div>


                    <span class="chat-count">
                        5
                    </span>


                </div>


                <!-- BUSCADOR -->

                <div class="chat-search">


                    <span>
                        🔍
                    </span>


                    <input
                        type="search"
                        placeholder="Buscar conversación..."
                    >


                </div>



                <!-- FILTROS -->

                <div class="chat-tabs">


                    <button
                        type="button"
                        class="chat-tab active"
                    >
                        Todos
                    </button>


                    <button
                        type="button"
                        class="chat-tab"
                    >
                        No leídos
                    </button>


                </div>



                <!-- CONVERSACIONES -->

                <div class="conversation-list">


                    <?php foreach (
                        $conversaciones as $index => $conversacion
                    ): ?>


                        <button
                            type="button"
                            class="
                                conversation
                                <?= $index === 0
                                    ? "active"
                                    : "" ?>
                            "
                        >


                            <div
                                class="
                                    conversation__avatar
                                    <?= htmlspecialchars(
                                        $conversacion["color"]
                                    ) ?>
                                "
                            >

                                <?= htmlspecialchars(
                                    $conversacion["inicial"]
                                ) ?>


                            </div>


                            <div class="conversation__content">


                                <div
                                    class="
                                        conversation__top
                                    "
                                >

                                    <strong>

                                        <?= htmlspecialchars(
                                            $conversacion["nombre"]
                                        ) ?>

                                    </strong>


                                    <small>

                                        <?= htmlspecialchars(
                                            $conversacion["hora"]
                                        ) ?>

                                    </small>

                                </div>


                                <span
                                    class="
                                        conversation__course
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $conversacion["curso"]
                                    ) ?>

                                </span>


                                <p>

                                    <?= htmlspecialchars(
                                        $conversacion["mensaje"]
                                    ) ?>

                                </p>


                            </div>


                            <?php if (
                                $conversacion["no_leidos"] > 0
                            ): ?>

                                <span
                                    class="
                                        conversation__badge
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $conversacion["no_leidos"]
                                    ) ?>

                                </span>

                            <?php endif; ?>


                        </button>


                    <?php endforeach; ?>


                </div>


            </aside>



            <!-- =================================================
                 CONVERSACIÓN
            ================================================== -->

            <section class="chat-window">


                <!-- CABECERA -->

                <header class="chat-window__header">


                    <div class="chat-contact">


                        <div
                            class="
                                chat-contact__avatar
                                purple
                            "
                        >
                            A
                        </div>


                        <div>

                            <strong>
                                Ana López
                            </strong>


                            <span>

                                Curso:
                                PHP desde cero

                            </span>

                        </div>


                    </div>


                    <div class="chat-window__actions">


                        <button
                            type="button"
                            aria-label="Información"
                        >
                            ⓘ
                        </button>


                        <button
                            type="button"
                            aria-label="Más opciones"
                        >
                            ⋮
                        </button>


                    </div>


                </header>



                <!-- INFORMACIÓN DEL CURSO -->

                <div class="chat-course-info">


                    <div>

                        <span>
                            CONVERSACIÓN DEL CURSO
                        </span>


                        <strong>
                            PHP desde cero
                        </strong>

                    </div>


                    <a
                        href="../Public/CursoPreambulo.php?id=1"
                        class="chat-course-link"
                    >
                        Ver curso →
                    </a>


                </div>



                <!-- MENSAJES -->

                <div class="messages">


                    <div class="messages__date">
                        Hoy
                    </div>


                    <?php foreach (
                        $mensajes as $mensaje
                    ): ?>


                        <div
                            class="
                                message-row
                                <?= $mensaje["propio"]
                                    ? "message-row--own"
                                    : "" ?>
                            "
                        >


                            <?php if (
                                !$mensaje["propio"]
                            ): ?>


                                <div
                                    class="
                                        message-avatar
                                        purple
                                    "
                                >

                                    <?= htmlspecialchars(
                                        $mensaje["inicial"]
                                    ) ?>

                                </div>


                            <?php endif; ?>


                            <div class="message">


                                <div class="message__bubble">

                                    <?= htmlspecialchars(
                                        $mensaje["mensaje"]
                                    ) ?>

                                </div>


                                <small class="message__time">

                                    <?= htmlspecialchars(
                                        $mensaje["hora"]
                                    ) ?>

                                    <?php if (
                                        $mensaje["propio"]
                                    ): ?>

                                        ✓✓

                                    <?php endif; ?>

                                </small>


                            </div>


                        </div>


                    <?php endforeach; ?>


                </div>



                <!-- ESCRIBIR -->

                <form
                    class="chat-input"
                    action="#"
                    method="POST"
                >


                    <button
                        type="button"
                        aria-label="Adjuntar archivo"
                    >
                        ＋
                    </button>


                    <input
                        type="text"
                        name="mensaje"
                        placeholder="Escribe un mensaje..."
                    >


                    <button
                        type="button"
                        aria-label="Emoji"
                    >
                        ☺
                    </button>


                    <button
                        type="submit"
                        class="chat-input__send"
                        aria-label="Enviar mensaje"
                    >
                        ↑
                    </button>


                </form>


            </section>


        </div>


    </main>


</div>



<script src="../js/Main.js"></script>


</body>

</html>
