
<?php

class Env
{
    public static function cargar(string $ruta): void
    {
        if (!is_readable($ruta)) {
            throw new RuntimeException(
                'No se encuentra el archivo .env'
            );
        }

        $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Ignorar comentarios.
            if (str_starts_with($linea, '#')) {
                continue;
            }

            // Separar nombre y valor.
            if (!str_contains($linea, '=')) {
                continue;
            }

            [$clave, $valor] = explode('=', $linea, 2);

            $clave = trim($clave);
            $valor = trim($valor);

            if ($clave === '') {
                continue;
            }

            // Eliminar comillas exteriores, si existen.
            if (
                strlen($valor) >= 2 &&
                (
                    ($valor[0] === '"' && str_ends_with($valor, '"')) ||
                    ($valor[0] === "'" && str_ends_with($valor, "'"))
                )
            ) {
                $valor = substr($valor, 1, -1);
            }

            $_ENV[$clave] = $valor;
        }
    }
}
