
<?php

require_once __DIR__ . '/../../Model/ENV.php';
require_once __DIR__ . '/../../Model/conexion.php';

$cursoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$cursoId || $cursoId <= 0) {
    http_response_code(400);
    exit('El identificador del curso no es válido.');
}

try {
    Env::cargar(__DIR__ . '/../../.env');

    $baseDatos = new Database();
    $pdo = $baseDatos->conectar();

    $sql = "SELECT
                c.id_curso,
                c.nombre_curso,
                c.descripcion,
                c.precio_curso,
                c.promedio_general,
                c.id_instructor,
                cat.nombre_categoria,
                u.nombre AS nombre_instructor,
                u.apellidos AS apellidos_instructor
            FROM cursos c
            INNER JOIN categorias cat
                ON cat.id_categoria = c.id_categoria
            INNER JOIN usuarios u
                ON u.id_usuario = c.id_instructor
            WHERE c.id_curso = :id
              AND c.estatus = 1
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $cursoId]);

    $datosCurso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datosCurso) {
        http_response_code(404);
        exit('El curso no existe o no está disponible.');
    }

    $curso = [
        'id' => (int) $datosCurso['id_curso'],
        'titulo' => $datosCurso['nombre_curso'],
        'descripcion' => $datosCurso['descripcion'] ?? '',
        'descripcion_corta' => $datosCurso['descripcion'] ?? '',
        'categoria' => $datosCurso['nombre_categoria'],
        'instructor' => trim(
            $datosCurso['nombre_instructor'] . ' ' .
            $datosCurso['apellidos_instructor']
        ),
        'rating' => $datosCurso['promedio_general'],
        'precio' => (float) $datosCurso['precio_curso'],
    ];

} catch (Throwable $e) {
    error_log('Error cargando el curso: ' . $e);
    http_response_code(500);
    exit('No fue posible cargar el curso. Inténtalo más tarde.');
}

// Los siguientes datos continúan siendo temporales
// hasta que los conectes a sus respectivas tablas.
$aprendizajes = [
    'Comprender los fundamentos de programación',
    'Trabajar con ejercicios prácticos',
    'Desarrollar pequeños proyectos'
];

$secciones = [];
$resenas = [];

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
        <?= htmlspecialchars($curso["titulo"]) ?>
        | Academia
    </title>

    <!-- CSS GENERAL -->
    <link
        rel="stylesheet"
        href="../css/Style.css"
    >

    <!-- CSS DEL CURSO -->
    <link
        rel="stylesheet"
        href="../css/Curso_preambulo.css"
    >
</head>

<body>
    <?php include("../Compartidas/Header.php")?>

    <!-- =====================================
         INFORMACIÓN PRINCIPAL
    ====================================== -->
    <main>
        <section class="course-hero">
            <div class="container">
                <!-- MIGAS -->
                <div class="breadcrumbs">
                    <a href="../Public/Index.php">
                        Inicio
                    </a>
                    <span>
                        /
                    </span>
                    <a href="../Public/Categorias.php">
                        <?= htmlspecialchars(
                            $curso["categoria"]
                        ) ?>
                    </a>
                    <span>
                        /
                    </span>
                    <span>
                        Curso
                    </span>
                </div>
                <div class="course-hero__grid">

                    <!-- =================================
                         INFORMACIÓN
                    ================================== -->
                    <div class="course-hero__info">
                        <span class="course-category">
                            <?= htmlspecialchars(
                                $curso["categoria"]
                            ) ?>
                        </span>
                        <h1>
                            <?= htmlspecialchars(
                                $curso["titulo"]
                            ) ?>
                        </h1>
                        <p class="course-short-description">
                            <?= htmlspecialchars(
                                $curso["descripcion_corta"]
                            ) ?>
                        </p>

                        <!-- RATING -->
                        <div class="course-rating">
                            <strong id="rating-promedio">0.00</strong>

                            <span class="stars" id="rating-estrellas"
                                aria-label="Calificación del curso">
                                ☆☆☆☆☆
                            </span>
                            <a href="#resenas">
                                <span id="rating-total">0</span>
                                reseñas
                            </a>
                            <span>·</span>
                            <span>
                                <?= (int) $curso["estudiantes"] ?>
                                estudiantes
                            </span>
                        </div>


                        <!-- INSTRUCTOR -->
                        <p class="course-instructor">
                            Creado por
                            <strong>
                                <?= htmlspecialchars(
                                    $curso["instructor"]
                                ) ?>
                            </strong>
                        </p>

                        <!-- INFORMACIÓN -->
                        <div class="course-meta">
                            <span>
                                ◷
                                <?= htmlspecialchars(
                                    $curso["duracion"]
                                ) ?>
                            </span>
                            <span>
                                ▣
                                <?= $curso["lecciones"] ?>
                                lecciones
                            </span>
                            <span>
                                ◉
                                <?= htmlspecialchars(
                                    $curso["nivel"]
                                ) ?>
                            </span>
                            <span>
                                A
                                <?= htmlspecialchars(
                                    $curso["idioma"]
                                ) ?>
                            </span>
                        </div>
                    </div>

                    <!-- =================================
                         TARJETA DE COMPRA
                    ================================== -->
                    <aside class="enrollment-card">
                        <!-- IMAGEN / VIDEO -->
                        <div class="course-preview">
                            <div class="course-preview__play">
                                ▶
                            </div>
                            <span>
                                Vista previa del curso
                            </span>
                        </div>
                        <div class="enrollment-card__body">
                            <!-- PRECIO -->
                            <div class="course-price">
                                <strong>
                                    $<?= $curso["precio"] ?>
                                </strong>
                                <del>
                                    $<?= $curso["precio_anterior"] ?>
                                </del>
                            </div>

                            <p class="price-note">
                                Acceso completo al curso
                            </p>

                            <!-- BOTÓN -->
                            
                            <button
                                type="button"
                                id="btn-inscribirse"
                                data-curso-id="<?= (int) $curso["id"] ?>"
                                class="btn btn--primary btn--large"
                            >
                                Inscribirme ahora
                            </button>

                            <p id="mensaje-pago" role="status"></p>

                            <p class="guarantee">
                                Acceso inmediato después
                                de la inscripción.
                            </p>

                            <!-- INCLUYE -->
                            <div class="includes">
                                <h3>
                                    Este curso incluye:
                                </h3>
                                <ul>
                                    <li>
                                        ✓
                                        <?= $curso["duracion"] ?>
                                        de contenido
                                    </li>
                                    <li>
                                        ✓
                                        <?= $curso["lecciones"] ?>
                                        lecciones
                                    </li>
                                    <li>
                                        ✓
                                        Acceso desde cualquier dispositivo
                                    </li>
                                    <?php if ($curso["certificado"]): ?>
                                        <li>
                                            ✓
                                            Certificado de finalización
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>

            </div>
        </section>

        <!-- =====================================
             CONTENIDO DEL CURSO
        ====================================== -->
        <section class="course-content">

            <div class="container course-content__layout">


                <!-- =================================
                     CONTENIDO PRINCIPAL
                ================================== -->

                <div class="course-main">


                    <!-- DESCRIPCIÓN -->

                    <section class="course-section">

                        <h2>
                            Sobre este curso
                        </h2>

                        <p>
                            <?= htmlspecialchars(
                                $curso["descripcion"]
                            ) ?>
                        </p>

                    </section>


                    <!-- APRENDIZAJES -->

                    <section class="course-section">

                        <h2>
                            Lo que aprenderás
                        </h2>


                        <div class="learning-grid">

                            <?php foreach ($aprendizajes as $aprendizaje): ?>

                                <div class="learning-item">

                                    <span>
                                        ✓
                                    </span>

                                    <p>
                                        <?= htmlspecialchars(
                                            $aprendizaje
                                        ) ?>
                                    </p>

                                </div>

                            <?php endforeach; ?>

                        </div>


                    </section>


                    <!-- CONTENIDO -->

                    <section class="course-section">

                        <div class="section-title-row">

                            <h2>
                                Contenido del curso
                            </h2>

                            <span>
                                <?= $curso["lecciones"] ?>
                                lecciones
                            </span>

                        </div>


                        <div class="course-sections">


                            <?php foreach ($secciones as $indice => $seccion): ?>

                                <details
                                    class="course-module"
                                    <?= $indice === 0
                                        ? "open"
                                        : "" ?>
                                >


                                    <summary>


                                        <div>

                                            <strong>

                                                <?= $indice + 1 ?>.

                                                <?= htmlspecialchars(
                                                    $seccion["titulo"]
                                                ) ?>

                                            </strong>

                                            <span>

                                                <?= $seccion["lecciones"] ?>
                                                lecciones ·
                                                <?= $seccion["duracion"] ?>

                                            </span>

                                        </div>


                                        <span class="module-arrow">
                                            +
                                        </span>


                                    </summary>


                                    <div class="module-content">

                                        <div>
                                            Lección de introducción
                                        </div>

                                        <div>
                                            Conceptos fundamentales
                                        </div>

                                        <div>
                                            Ejercicios prácticos
                                        </div>

                                    </div>


                                </details>

                            <?php endforeach; ?>


                        </div>

                    </section>


                    <!-- REQUISITOS -->

                    <section class="course-section">

                        <h2>
                            Requisitos
                        </h2>

                        <ul class="requirements">

                            <li>
                                No necesitas experiencia previa.
                            </li>

                            <li>
                                Tener una computadora con conexión a internet.
                            </li>

                            <li>
                                Tener interés por aprender programación.
                            </li>

                        </ul>

                    </section>


                    <!-- INSTRUCTOR -->

                    <section class="course-section instructor-section">


                        <h2>
                            Instructor
                        </h2>


                        <div class="instructor">


                            <div class="instructor__avatar">
                                JP
                            </div>


                            <div>

                                <h3>
                                    <?= htmlspecialchars(
                                        $curso["instructor"]
                                    ) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars(
                                        $curso["instructor_descripcion"]
                                    ) ?>
                                </p>

                            </div>


                        </div>


                    </section>


                    <!-- =================================
                         RESEÑAS
                    ================================== -->
                    <section class="course-section" id="resenas">
                        <h2>Opiniones de estudiantes</h2>
                        <!-- RESUMEN -->
                        <div class="reviews-summary">
                            <div class="reviews-score">
                                <strong id="resumen-promedio">0.00</strong>
                                <div class="stars" id="resumen-estrellas">
                                    ☆☆☆☆☆
                                </div>
                                <span id="resumen-total">
                                    Cargando reseñas...
                                </span>
                            </div>

                            <!-- DISTRIBUCIÓN DE ESTRELLAS -->
                            <div class="rating-bars">
                                <?php for ($estrella = 5; $estrella >= 1; $estrella--): ?>
                                    <div class="rating-bar">
                                        <span><?= $estrella ?></span>
                                        <div class="rating-bar__track">
                                            <span
                                                id="barra-<?= $estrella ?>"
                                                style="width: 0%;"
                                            ></span>
                                        </div>
                                        <small id="porcentaje-<?= $estrella ?>">
                                            0%
                                        </small>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <!-- FORMULARIO DE RESEÑA -->
                        <div class="review-form-container">

                            <h3>Comparte tu opinión</h3>

                            <form id="form-resena">

                                <label for="calificacion">
                                    Calificación
                                </label>

                                <select id="calificacion" required>
                                    <option value="">Selecciona una calificación</option>
                                    <option value="5">★★★★★ - Excelente</option>
                                    <option value="4">★★★★☆ - Muy bueno</option>
                                    <option value="3">★★★☆☆ - Bueno</option>
                                    <option value="2">★★☆☆☆ - Regular</option>
                                    <option value="1">★☆☆☆☆ - Malo</option>
                                </select>

                                <label for="titulo-resena">
                                    Título
                                </label>

                                <input
                                    type="text"
                                    id="titulo-resena"
                                    maxlength="200"
                                    placeholder="Ej. Excelente curso"
                                >

                                <label for="texto-resena">
                                    Comentario
                                </label>

                                <textarea
                                    id="texto-resena"
                                    maxlength="5000"
                                    required
                                    placeholder="Cuéntanos tu experiencia..."
                                ></textarea>

                                <button
                                    type="submit"
                                    class="btn btn--primary"
                                >
                                    Enviar reseña
                                </button>

                                <p id="mensaje-resena" role="status"></p>

                            </form>

                        </div>

                        <!-- LISTA DE RESEÑAS -->
                        <div class="reviews-list" id="lista-resenas">
                            <p>Cargando opiniones...</p>
                        </div>

                    </section>
                </div>
            </div>

        </section>
    </main>


    <!-- =====================================
         FOOTER
    ====================================== -->
<?php include("../Compartidas/Footer.php")?>


    <!-- JAVASCRIPT -->
    <script>
        // ID del curso que se está mostrando.
        const idCurso = <?= (int) $curso["id"] ?>;

        // Ruta desde View/Public/CursoPreambulo.php
        // hacia la carpeta api de la raíz del proyecto.
        const apiURL = '../../api/resenas.php?curso='
            + encodeURIComponent(idCurso);

        async function cargarResenas() {
            const respuesta = await fetch(apiURL, {
                credentials: 'same-origin'
            });

            const datos = await respuesta.json();

            if (!respuesta.ok) {
                throw new Error(
                    datos.error || 'No se pudieron cargar las reseñas.'
                );
            }

            const resumen = datos.resumen;
            const total = Number(resumen.total_resenas);
            const promedio = Number(resumen.promedio);

            // Actualizar el rating de la parte superior.
            document.getElementById('rating-promedio')
                .textContent = promedio.toFixed(2);

            document.getElementById('rating-total')
                .textContent = total;

            document.getElementById('rating-estrellas')
                .textContent = estrellas(promedio);

            // Actualizar el resumen de opiniones.
            document.getElementById('resumen-promedio')
                .textContent = promedio.toFixed(2);

            document.getElementById('resumen-total')
                .textContent = `${total} reseña(s)`;

            document.getElementById('resumen-estrellas')
                .textContent = estrellas(promedio);

            // Actualizar las barras de distribución.
            for (let numero = 1; numero <= 5; numero++) {
                const cantidad = Number(
                    resumen[`estrellas_${numero}`] || 0
                );

                const porcentaje = total > 0
                    ? (cantidad / total) * 100
                    : 0;

                document.getElementById(`barra-${numero}`)
                    .style.width = `${porcentaje}%`;

                document.getElementById(`porcentaje-${numero}`)
                    .textContent = `${Math.round(porcentaje)}%`;
            }

            // Mostrar las reseñas.
            const lista = document.getElementById('lista-resenas');
            lista.replaceChildren();

            if (datos.resenas.length === 0) {
                const vacio = document.createElement('p');
                vacio.textContent =
                    'Este curso todavía no tiene reseñas.';
                lista.appendChild(vacio);
                return;
            }

            datos.resenas.forEach(resena => {
                const articulo = document.createElement('article');
                articulo.className = 'review';

                const avatar = document.createElement('div');
                avatar.className = 'review__avatar';

                const nombreCompleto =
                    `${resena.nombre} ${resena.apellidos}`.trim();

                avatar.textContent = nombreCompleto
                    .charAt(0).toUpperCase();

                const contenido = document.createElement('div');
                contenido.className = 'review__content';

                const encabezado = document.createElement('div');
                encabezado.className = 'review__header';

                const nombre = document.createElement('strong');
                nombre.textContent = nombreCompleto;

                const calificacion = Number(resena.calificacion);
                const estrellasResena = document.createElement('span');

                estrellasResena.className = 'stars';
                estrellasResena.textContent = estrellas(calificacion);

                encabezado.append(nombre, estrellasResena);

                const titulo = document.createElement('h4');
                titulo.textContent =
                    resena.titulo || 'Opinión del estudiante';

                const comentario = document.createElement('p');
                comentario.textContent = resena.resena;

                contenido.append(encabezado, titulo, comentario);
                articulo.append(avatar, contenido);
                lista.appendChild(articulo);
            });
        }

        function estrellas(valor) {
            const llenas = Math.max(
                0,
                Math.min(5, Math.round(Number(valor)))
            );

            return '★'.repeat(llenas) + '☆'.repeat(5 - llenas);
        }

        // Enviar una reseña a la API.
        document.getElementById('form-resena').addEventListener(
            'submit',
            async function (evento) {
                evento.preventDefault();

                const mensaje =
                    document.getElementById('mensaje-resena');

                const boton = this.querySelector('button[type="submit"]');

                const cuerpo = {
                    calificacion: Number(
                        document.getElementById('calificacion').value
                    ),
                    titulo: document.getElementById('titulo-resena')
                        .value.trim(),
                    resena: document.getElementById('texto-resena')
                        .value.trim()
                };

                mensaje.textContent = 'Enviando reseña...';
                boton.disabled = true;

                try {
                    const respuesta = await fetch(apiURL, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(cuerpo)
                    });

                    const datos = await respuesta.json();

                    if (!respuesta.ok) {
                        throw new Error(
                            datos.error || 'No se pudo guardar la reseña.'
                        );
                    }

                    mensaje.textContent = datos.mensaje;

                    this.reset();

                    // Volver a consultar el promedio y las opiniones.
                    await cargarResenas();

                } catch (error) {
                    mensaje.textContent = error.message;
                } finally {
                    boton.disabled = false;
                }
            }
        );

        // Cargar las reseñas al abrir el preámbulo.
        cargarResenas().catch(error => {
            document.getElementById('resumen-total')
                .textContent = error.message;

            document.getElementById('lista-resenas')
                .textContent = 'No fue posible cargar las opiniones.';
        });
    </script>
    <script>        
        const botonInscripcion = document.getElementById('btn-inscribirse');
        const mensajePago = document.getElementById('mensaje-pago');

        botonInscripcion.addEventListener('click', async () => {
            const idCurso = Number(botonInscripcion.dataset.cursoId);

            botonInscripcion.disabled = true;
            mensajePago.textContent = 'Preparando el pago...';

            try {
                const respuesta = await fetch('../../api/pagos.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_curso: idCurso
                    })
                });

                const datos = await respuesta.json();

                if (respuesta.status === 401) {
                    window.location.href =
                        `../Auth/Login.php?curso=${encodeURIComponent(idCurso)}`;
                    return;
                }

                if (!respuesta.ok || !datos.success) {
                    throw new Error(
                        datos.message || 'No se pudo iniciar el pago.'
                    );
                }

                if (!datos.checkout_url) {
                    throw new Error('Stripe no devolvió una URL de pago.');
                }

                window.location.href = datos.checkout_url;

            } catch (error) {
                mensajePago.textContent = error.message;
                botonInscripcion.disabled = false;
            }
        });
    </script>

    <script src="../js/Main.js"></script>

</body>

</html>
