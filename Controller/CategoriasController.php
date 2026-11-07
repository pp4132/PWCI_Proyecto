<?php
require_once __DIR__ . "/../Model/conexion.php";
require_once __DIR__ . "/../Model/Categoria.php";
require_once __DIR__ . "/../Model/Curso.php";

class CategoriasController
{
    private Categoria $modeloCategoria;
    private Curso $modeloCurso;

    public function __construct()
    {
        $database = new Database();

        $conexion = $database->conectar();

        $this->modeloCategoria = new Categoria($conexion);
        $this->modeloCurso = new Curso($conexion);
    }

    /**
     * Cargar información de la página de categorías.
     */
    public function index(): array
    {
        return [
            'categorias' => $this->modeloCategoria->obtenerTodas(),

            'cursosDestacados' =>
                $this->modeloCurso->obtenerDestacadosConEstudiantes(3)
        ];
    }
}