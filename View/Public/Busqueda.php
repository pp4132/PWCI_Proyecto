<?php

    /*
     * =========================================
     * PARÁMETROS DE BÚSQUEDA
     * =========================================
     *
     * Posteriormente estos valores serán
     * procesados por el Controller.
     */

    $busqueda = isset($_GET["q"])
        ? trim($_GET["q"])
        : "";

    $categoriaSeleccionada = isset($_GET["categoria"])
        ? trim($_GET["categoria"])
        : "";


    /*
     * =========================================
     * DATOS TEMPORALES
     * =========================================
     *
     * Más adelante vendrán desde el Controller.
     */

    $cursos = [

        [
            "id" => 1,
            "titulo" => "JavaScript desde cero",
            "categoria" => "Programación",
            "instructor" => "Juan Pérez",
            "rating" => "4.8",
            "estudiantes" => 325,
            "nivel" => "Principiante",
            "duracion" => "12 horas",
            "precio" => 299
        ],

        [
            "id" => 2,
            "titulo" => "Desarrollo Web con PHP",
            "categoria" => "Programación",
            "instructor" => "Ana García",
            "rating" => "4.9",
            "estudiantes" => 421,
            "nivel" => "Intermedio",
            "duracion" => "18 horas",
            "precio" => 399
        ],

        [
            "id" => 3,
            "titulo" => "Diseño UI/UX moderno",
            "categoria" => "Diseño",
            "instructor" => "María López",
            "rating" => "4.9",
            "estudiantes" => 214,
            "nivel" => "Principiante",
            "duracion" => "10 horas",
            "precio" => 349
        ],

        [
            "id" => 4,
            "titulo" => "Marketing Digital",
            "categoria" => "Marketing",
            "instructor" => "Carlos Ramírez",
            "rating" => "4.7",
            "estudiantes" => 187,
            "nivel" => "Intermedio",
            "duracion" => "8 horas",
            "precio" => 249
        ],

        [
            "id" => 5,
            "titulo" => "Python para principiantes",
            "categoria" => "Programación",
            "instructor" => "Luis Hernández",
            "rating" => "4.8",
            "estudiantes" => 512,
            "nivel" => "Principiante",
            "duracion" => "15 horas",
            "precio" => 329
        ],

        [
            "id" => 6,
            "titulo" => "Fotografía digital",
            "categoria" => "Fotografía",
            "instructor" => "Sofía Martínez",
            "rating" => "4.6",
            "estudiantes" => 143,
            "nivel" => "Principiante",
            "duracion" => "7 horas",
            "precio" => 199
        ]

    ];


    /*
     * =========================================
     * FILTRO TEMPORAL
     * =========================================
     *
     * Esto únicamente sirve para que el prototipo
     * reaccione a q y categoria.
     *
     * Posteriormente esta lógica pertenecerá
     * al Controller.
     */

    $resultados = array_filter(
        $cursos,
        function ($curso) use (
            $busqueda,
            $categoriaSeleccionada
        ) {

            $coincideBusqueda = true;
            $coincideCategoria = true;


            if ($busqueda !== "") {

                $texto =
                    $curso["titulo"] . " " .
                    $curso["categoria"] . " " .
                    $curso["instructor"];

                $coincideBusqueda =
                    stripos(
                        $texto,
                        $busqueda
                    ) !== false;
            }


            if ($categoriaSeleccionada !== "") {

                $coincideCategoria =
                    strcasecmp(
                        $curso["categoria"],
                        $categoriaSeleccionada
                    ) === 0;
            }


            return
                $coincideBusqueda &&
                $coincideCategoria;
        }
    );


    /*
     * =========================================
     * CATEGORÍAS PARA EL FILTRO
     * =========================================
     */

    $categorias = [
        "Programación",
        "Diseño",
        "Negocios",
        "Marketing",
        "Fotografía",
        "Educación",
        "Idiomas",
        "Desarrollo personal"
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

    <title>Buscar cursos | Academia</title>


    <!-- CSS GENERAL -->

    <link
        rel="stylesheet"
        href="../css/Style.css"
    >


    <!-- CSS DE BÚSQUEDA -->

    <link
        rel="stylesheet"
        href="../css/Busqueda.css"
    >

</head>


<body>


    <!-- =====================================
         NAVBAR
    ====================================== -->
    <?php include("../Compartidas/Header.php")?>

    <!-- =====================================
         CONTENIDO
    ====================================== -->

    <main>


        <!-- =================================
             ENCABEZADO
        ================================== -->

        <section class="search-header">

            <div class="container">


                <span class="section-tag">
                    EXPLORA NUESTROS CURSOS
                </span>


                <h1>
                    Encuentra algo que
                    <span>quieras aprender.</span>
                </h1>


                <!-- BUSCADOR -->

                <form
                    action="../Public/Busqueda.php"
                    method="GET"
                    class="main-search"
                >


                    <div class="main-search__input">

                        <span>
                            🔍
                        </span>

                        <input
                            type="search"
                            name="q"
                            value="<?= htmlspecialchars($busqueda) ?>"
                            placeholder="Buscar por nombre, tema o instructor..."
                            aria-label="Buscar cursos"
                        >

                    </div>


                    <?php if ($categoriaSeleccionada !== ""): ?>

                        <input
                            type="hidden"
                            name="categoria"
                            value="<?= htmlspecialchars($categoriaSeleccionada) ?>"
                        >

                    <?php endif; ?>


                    <button
                        type="submit"
                        class="btn btn--primary"
                    >
                        Buscar
                    </button>


                </form>


            </div>

        </section>


        <!-- =================================
             RESULTADOS
        ================================== -->

        <section class="search-results">

            <div class="container">


                <!-- BARRA SUPERIOR -->

                <div class="results-toolbar">


                    <div>

                        <?php if ($busqueda !== ""): ?>

                            <h2>
                                Resultados para
                                "<?= htmlspecialchars($busqueda) ?>"
                            </h2>

                        <?php elseif ($categoriaSeleccionada !== ""): ?>

                            <h2>
                                Cursos de
                                <?= htmlspecialchars($categoriaSeleccionada) ?>
                            </h2>

                        <?php else: ?>

                            <h2>
                                Todos los cursos
                            </h2>

                        <?php endif; ?>


                        <p>
                            <?= count($resultados) ?>
                            cursos encontrados
                        </p>

                    </div>


                    <!-- ORDENAMIENTO -->

                    <div class="sort-control">

                        <label for="ordenar">
                            Ordenar por
                        </label>

                        <select id="ordenar">

                            <option value="relevancia">
                                Relevancia
                            </option>

                            <option value="rating">
                                Mejor valorados
                            </option>

                            <option value="precio-menor">
                                Precio: menor a mayor
                            </option>

                            <option value="precio-mayor">
                                Precio: mayor a menor
                            </option>

                        </select>

                    </div>


                </div>


                <!-- =================================
                     CONTENIDO PRINCIPAL
                ================================== -->

                <div class="search-layout">


                    <!-- =================================
                         FILTROS
                    ================================== -->

                    <aside class="filters">


                        <div class="filters__header">

                            <h3>
                                Filtrar
                            </h3>

                            <a href="../Public/Busqueda.php">
                                Limpiar
                            </a>

                        </div>


                        <!-- CATEGORÍAS -->

                        <div class="filter-group">

                            <h4>
                                Categoría
                            </h4>


                            <?php foreach ($categorias as $categoria): ?>

                                <label class="filter-option">

                                    <input
                                        type="radio"
                                        name="categoria"
                                        value="<?= htmlspecialchars($categoria) ?>"
                                        <?= $categoriaSeleccionada === $categoria
                                            ? "checked"
                                            : "" ?>
                                        onchange="
                                            window.location.href =
                                            'Busqueda.php?categoria=' +
                                            encodeURIComponent(this.value);
                                        "
                                    >

                                    <span>
                                        <?= htmlspecialchars($categoria) ?>
                                    </span>

                                </label>

                            <?php endforeach; ?>


                        </div>


                        <!-- NIVEL -->

                        <div class="filter-group">

                            <h4>
                                Nivel
                            </h4>


                            <label class="filter-option">

                                <input
                                    type="checkbox"
                                    value="Principiante"
                                >

                                <span>
                                    Principiante
                                </span>

                            </label>


                            <label class="filter-option">

                                <input
                                    type="checkbox"
                                    value="Intermedio"
                                >

                                <span>
                                    Intermedio
                                </span>

                            </label>


                            <label class="filter-option">

                                <input
                                    type="checkbox"
                                    value="Avanzado"
                                >

                                <span>
                                    Avanzado
                                </span>

                            </label>

                        </div>


                        <!-- PRECIO -->

                        <div class="filter-group">

                            <h4>
                                Precio
                            </h4>


                            <label class="filter-option">

                                <input
                                    type="checkbox"
                                    value="gratis"
                                >

                                <span>
                                    Gratis
                                </span>

                            </label>


                            <label class="filter-option">

                                <input
                                    type="checkbox"
                                    value="pago"
                                >

                                <span>
                                    De pago
                                </span>

                            </label>

                        </div>


                    </aside>


                    <!-- =================================
                         LISTADO
                    ================================== -->

                    <div class="results-list">


                        <?php if (count($resultados) > 0): ?>


                            <?php foreach ($resultados as $curso): ?>

                                <article class="result-card">


                                    <!-- IMAGEN -->

                                    <div class="result-card__image">

                                        <span>
                                            <?= htmlspecialchars(
                                                $curso["categoria"]
                                            ) ?>
                                        </span>

                                    </div>


                                    <!-- INFORMACIÓN -->

                                    <div class="result-card__content">


                                        <span class="result-card__category">

                                            <?= htmlspecialchars(
                                                $curso["categoria"]
                                            ) ?>

                                        </span>


                                        <h3>

                                            <a
                                                href="CursoPreambulo.php?id=<?= $curso["id"] ?>"
                                            >

                                                <?= htmlspecialchars(
                                                    $curso["titulo"]
                                                ) ?>

                                            </a>

                                        </h3>


                                        <p class="result-card__description">

                                            Aprende los fundamentos y
                                            desarrolla habilidades prácticas
                                            mediante ejercicios y proyectos.

                                        </p>


                                        <p class="result-card__instructor">

                                            Instructor:
                                            <strong>
                                                <?= htmlspecialchars(
                                                    $curso["instructor"]
                                                ) ?>
                                            </strong>

                                        </p>


                                        <!-- METADATOS -->

                                        <div class="result-card__meta">

                                            <span>
                                                ⭐
                                                <?= $curso["rating"] ?>
                                            </span>

                                            <span>
                                                👥
                                                <?= $curso["estudiantes"] ?>
                                            </span>

                                            <span>
                                                ◷
                                                <?= $curso["duracion"] ?>
                                            </span>

                                            <span>
                                                <?= htmlspecialchars(
                                                    $curso["nivel"]
                                                ) ?>
                                            </span>

                                        </div>


                                    </div>


                                    <!-- PRECIO -->

                                    <div class="result-card__price">


                                        <strong>
                                            $<?= $curso["precio"] ?>
                                        </strong>


                                        <a
                                            href="CursoPreambulo.php?id=<?= $curso["id"] ?>"
                                            class="btn btn--primary"
                                        >
                                            Ver curso
                                        </a>


                                    </div>


                                </article>

                            <?php endforeach; ?>


                        <?php else: ?>


                            <!-- =================================
                                 SIN RESULTADOS
                            ================================== -->

                            <div class="no-results">


                                <div class="no-results__icon">
                                    🔍
                                </div>


                                <h2>
                                    No encontramos cursos
                                </h2>


                                <p>
                                    No hay cursos que coincidan con
                                    tu búsqueda. Intenta utilizar
                                    otros términos.
                                </p>


                                <a
                                    href="../Public/Busqueda.php"
                                    class="btn btn--primary"
                                >
                                    Ver todos los cursos
                                </a>


                            </div>


                        <?php endif; ?>


                    </div>


                </div>


            </div>

        </section>


    </main>


    <!-- =====================================
         FOOTER
    ====================================== -->
    <?php include("../Compartidas/Footer.php")?>

    <!-- JAVASCRIPT -->

    <script src="../js/Main.js"></script>

</body>

</html>
