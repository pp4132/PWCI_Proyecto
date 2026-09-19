    <header class="navbar">

        <div class="navbar__logo">
            <a href="Index.php">
                ACADEMIA
            </a>
        </div>

        <nav class="navbar__links">
            <a href="../Public/Categorias.php">Categorías</a>
            <a href="../Public/Busqueda.php">Explorar</a>
        </nav>

        <form class="navbar__search" action="../Public/Busqueda.php" method="GET">
            <input
                type="text"
                name="q"
                placeholder="¿Qué quieres aprender?"
            >

            <button type="submit">
                🔍
            </button>
        </form>

        <div class="navbar__actions">
            <a href="../Auth/Login.php" class="btn btn--secondary">
                Iniciar sesión
            </a>

            <a href="../Auth/Registro.php" class="btn btn--primary">
                Registrarse
            </a>
        </div>

    </header>