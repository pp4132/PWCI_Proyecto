<?php
require_once __DIR__ . "/../Model/conexion.php";
require_once __DIR__ . "/../Model/Curso.php";
require_once __DIR__ . "/../Model/Categoria.php";

class IndexController
{
    private PDO $conexion;
    private Curso $modeloCurso;
    private Categoria $modeloCategoria;

    public function __construct()
    {
        $database = new Database();

        $this->conexion = $database->conectar();

        $this->modeloCurso = new Curso($this->conexion);
        $this->modeloCategoria = new Categoria($this->conexion);
    }

    /**
     * Obtener información para la página principal.
     */
    public function index(): array
    {
        return [
            'categorias' => $this->modeloCategoria->obtenerTodas(),
            'cursosDestacados' => $this->modeloCurso->obtenerDestacados(4)
        ];
    }
}