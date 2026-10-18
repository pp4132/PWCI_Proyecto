
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aprender curso | MiAcademia</title>

    <!-- Tu CSS principal -->
    <link rel="stylesheet" href="../css/Style.css">

    <!-- Estilos específicos -->
    <link rel="stylesheet" href="../css/Cursoin.css">

    <script src="../js/curso.js" defer></script>
</head>

<body class="learning-page">

    <!-- CABECERA -->
    <?php include("../Compartidas/Header.php")?>


    <main class="learning-layout">

        <!-- INFORMACIÓN DEL CURSO -->
        <section class="learning-heading">

            <div class="learning-heading__breadcrumb">
                <a href="#">Mis cursos</a>
                <span>/</span>
                <span>Desarrollo web</span>
            </div>

            <h1>Desarrollo web desde cero</h1>

            <p>
                Aprende HTML, CSS y JavaScript creando tus
                primeros proyectos web.
            </p>

        </section>


        <!-- CUERPO PRINCIPAL -->
        <div class="learning-grid">

            <!-- ÍNDICE LATERAL -->
            <aside class="course-sidebar" aria-label="Índice del curso">

                <div class="progress-card">

                    <div class="progress-card__header">
                        <h2>Tu progreso</h2>
                        <span id="progress-percent">0 %</span>
                    </div>

                    <div
                        class="progress-track"
                        id="progress-track"
                        role="progressbar"
                        aria-label="Progreso del curso"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        aria-valuenow="0">

                        <div
                            class="progress-track__fill"
                            id="progress-fill">
                        </div>

                    </div>

                    <p id="progress-detail">
                        0 de 5 secciones completadas
                    </p>

                </div>


                <div class="curriculum-card">

                    <div class="curriculum-card__header">
                        <h2>Contenido del curso</h2>

                        <span id="lesson-total">
                            5 secciones
                        </span>
                    </div>

                    <div
                        id="course-index"
                        class="course-index">
                        <!-- Generado por JavaScript -->
                    </div>

                </div>

            </aside>


            <!-- ÁREA DE APRENDIZAJE -->
            <section class="learning-content">

                <!-- CONTENIDO DE LA SECCIÓN -->
                <div id="section-view">

                    <div class="content-heading">

                        <div>
                            <span
                                class="content-eyebrow"
                                id="section-number">
                                SECCIÓN 1
                            </span>

                            <h2 id="section-title">
                                Introducción
                            </h2>

                            <p id="section-description">
                                Comienza tu recorrido de aprendizaje.
                            </p>
                        </div>

                        <span
                            class="content-counter"
                            id="material-counter">
                            Material 1 de 3
                        </span>

                    </div>


                    <!-- REPRODUCTOR -->
                    <div
                        id="material-view"
                        class="material-view">
                    </div>


                    <!-- NAVEGACIÓN DEL MATERIAL -->
                    <div class="material-navigation">

                        <button
                            type="button"
                            class="btn btn--outline"
                            id="previous-material">
                            ← Anterior
                        </button>

                        <button
                            type="button"
                            class="btn btn--primary"
                            id="next-material">
                            Siguiente material →
                        </button>

                    </div>


                    <!-- FINAL DE SECCIÓN -->
                    <section class="section-finish">

                        <div class="section-finish__info">

                            <div class="section-finish__icon">
                                ✓
                            </div>

                            <div>
                                <h3>¿Has terminado de estudiar?</h3>

                                <p>
                                    Revisa todos los materiales para
                                    acceder a la evaluación de esta sección.
                                </p>

                                <span id="materials-status">
                                    0 de 3 materiales revisados
                                </span>
                            </div>

                        </div>

                        <div class="section-finish__footer">

                            <div class="section-finish__requirements">
                                <span>Evaluación</span>
                                <strong>3 preguntas de ejemplo</strong>
                                <small>
                                    70 % para aprobar · 3 intentos
                                </small>
                            </div>

                            <button
                                type="button"
                                class="btn btn--primary btn--large"
                                id="finish-section"
                                disabled>
                                Finalizar sección →
                            </button>

                        </div>

                    </section>

                </div>


                <!-- EVALUACIÓN -->
                <section
                    id="quiz-view"
                    class="quiz-view"
                    hidden>

                    <div class="content-heading">
                        <div>
                            <span class="content-eyebrow">
                                EVALUACIÓN DE LA SECCIÓN
                            </span>

                            <h2 id="quiz-title">
                                Comprueba lo aprendido
                            </h2>

                            <p>
                                Responde a las preguntas para
                                desbloquear la siguiente sección.
                            </p>
                        </div>
                    </div>

                    <div class="quiz-summary">

                        <div>
                            <span>Intentos utilizados</span>
                            <strong id="attempts-used">0 de 3</strong>
                        </div>

                        <div>
                            <span>Nota mínima</span>
                            <strong>70 %</strong>
                        </div>

                    </div>

                    <form id="quiz-form">

                        <div id="quiz-questions"></div>

                        <div class="quiz-actions">

                            <button
                                type="button"
                                class="btn btn--outline"
                                id="back-to-material">
                                ← Volver al material
                            </button>

                            <button
                                type="submit"
                                class="btn btn--primary btn--large"
                                id="submit-quiz">
                                Enviar respuestas
                            </button>

                        </div>

                    </form>

                    <div
                        id="quiz-result"
                        class="quiz-result"
                        role="status"
                        aria-live="polite"
                        hidden>
                    </div>

                    <button
                        type="button"
                        class="btn btn--primary btn--large"
                        id="continue-course"
                        hidden>
                        Continuar al siguiente paso →
                    </button>

                </section>

            </section>

        </div>

    </main>


    <?php include("../Compartidas/Footer.php")?>

</body>
</html>
