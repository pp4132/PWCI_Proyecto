<?php
require_once __DIR__ . "/conexion.php";

class Usuario
{
    private PDO $conexion;

    public function __construct()
    {
        $baseDatos = new Database();
        $this->conexion = $baseDatos->conectar();
    }

    public function existeEmail(string $email): bool
    {
        $sql = "SELECT id_usuario
                FROM usuarios
                WHERE email = :email
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["email" => $email]);

        return $consulta->fetch() !== false;
    }

    public function registrar(
        string $nombre,
        string $apellidos,
        string $password,
        string $email,
        string $genero,
        int $idRol
    ): bool {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO usuarios
                (nombre, apellidos, password, email, genero, id_rol)
                VALUES
                (:nombre, :apellidos, :password, :email, :genero, :id_rol)";

        $consulta = $this->conexion->prepare($sql);

        return $consulta->execute([
            "nombre"   => $nombre,
            "apellidos" => $apellidos,
            "password" => $passwordHash,
            "email"    => $email,
            "genero"   => $genero,
            "id_rol"   => $idRol
        ]);
    }

    public function obtenerIdRol(string $nombreRol): ?int
    {
        $sql = "SELECT id_rol
                FROM roles
                WHERE nombre_rol = :nombre
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(["nombre" => $nombreRol]);

        $resultado = $consulta->fetch();

        return $resultado
            ? (int) $resultado["id_rol"]
            : null;
    }

    public function autenticar(string $email, string $password): ?array {
        $sql = "SELECT
                    u.id_usuario,
                    u.nombre,
                    u.apellidos,
                    u.email,
                    u.password,
                    u.id_rol,
                    r.nombre_rol,
                    u.estatus
                FROM usuarios AS u
                INNER JOIN roles AS r
                    ON u.id_rol = r.id_rol
                WHERE u.email = :email
                LIMIT 1";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            "email" => $email
        ]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        // El correo no existe o la contraseña es incorrecta.
        if (
            !$usuario ||
            !password_verify($password, $usuario["password"])
        ) {
            return null;
        }

        // Solo se permite el acceso a cuentas activas.
        if ($usuario["estatus"] !== "Activo") {
            return null;
        }

        // No exponemos el hash de la contraseña al controlador.
        unset($usuario["password"]);

        return $usuario;
    }
    
}