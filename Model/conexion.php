<?php
require_once __DIR__ . "/Env.php";
class Database
{
    private ?PDO $conexion = null;

    public function conectar(): PDO
    {
        if ($this->conexion !== null) {
            return $this->conexion;
        }

        // Cargar las variables del archivo .env
        Env::cargar(__DIR__ . "/../.env");

        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $dbname = $_ENV['DB_NAME'] ?? '';
        $username = $_ENV['DB_USER'] ?? '';
        $password = $_ENV['DB_PASS'] ?? '';

        if ($dbname === '' || $username === '') {
            throw new RuntimeException(
                'Faltan variables para conectar con MySQL.'
            );
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

        try {
            $this->conexion = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return $this->conexion;

        } catch (PDOException $e) {
            error_log($e->getMessage());

            throw new RuntimeException(
                'No se pudo conectar con MySQL.',
                0,
                $e
            );
        }
    }
}
