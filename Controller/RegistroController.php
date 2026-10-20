<?php
require_once __DIR__ . "/../Model/Usuario.php";

class RegistroController
{
    public function registrar(): array
    {
        $error = "";
        $mensaje = "";

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            return compact("error", "mensaje");
        }

        $nombre = trim($_POST["nombre"] ?? "");
        $apellidos = trim($_POST["apellidos"] ?? "");
        $email = trim($_POST["correo"] ?? "");
        $genero = $_POST["genero"] ?? "";
        $rol = $_POST["rol"] ?? "";
        $password = $_POST["password"] ?? "";
        $confirmacion = $_POST["password_confirmation"] ?? "";

        try {
            if (
                $nombre === "" ||
                $apellidos === "" ||
                !filter_var($email, FILTER_VALIDATE_EMAIL)
            ) {
                throw new InvalidArgumentException(
                    "Completa correctamente los campos."
                );
            }

            if (
                mb_strlen($nombre) > 100 ||
                mb_strlen($apellidos) > 150 ||
                strlen($email) > 255
            ) {
                throw new InvalidArgumentException(
                    "La longitud de un campo es demasiado grande."
                );
            }

            if (strlen($password) < 8) {
                throw new InvalidArgumentException(
                    "La contraseña debe tener al menos 8 caracteres."
                );
            }

            if ($password !== $confirmacion) {
                throw new InvalidArgumentException(
                    "Las contraseñas no coinciden."
                );
            }

            if (!isset($_POST["terms"])) {
                throw new InvalidArgumentException(
                    "Debes aceptar los términos y condiciones."
                );
            }

            if (!in_array(
                $genero,
                ["Hombre", "Mujer", "No contestar"],
                true
            )) {
                throw new InvalidArgumentException(
                    "Selecciona un género válido."
                );
            }

            if (!in_array(
                $rol,
                ["Estudiante", "Instructor"],
                true
            )) {
                throw new InvalidArgumentException(
                    "Selecciona un rol válido."
                );
            }

            $usuario = new Usuario();

            if ($usuario->existeEmail($email)) {
                throw new InvalidArgumentException(
                    "Este correo ya está registrado."
                );
            }

            $idRol = $usuario->obtenerIdRol($rol);

            if ($idRol === null) {
                throw new RuntimeException(
                    "El rol seleccionado no existe."
                );
            }

            $usuario->registrar(
                $nombre,
                $apellidos,
                $password,
                $email,
                $genero,
                $idRol
            );

            $mensaje = "¡Tu cuenta se creó correctamente!";

        } catch (InvalidArgumentException $e) {
            $error = $e->getMessage();

        } catch (PDOException $e) {
            error_log($e->getMessage());

            $error = $e->getCode() === "23000"
                ? "El correo ya está registrado o existe un dato duplicado."
                : "No se pudo registrar la cuenta.";

        } catch (Throwable $e) {
            error_log(
                get_class($e) . ": " . $e->getMessage()
            );

            $error = "Error: " . $e->getMessage();
        }

        return compact("error", "mensaje");
    }
}
