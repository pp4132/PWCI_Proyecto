
<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Middleware/AuthMiddleware.php";
require_once __DIR__ . "/../Model/conexion.php";
require_once __DIR__ . "/../Model/Curso.php";

AuthMiddleware::requiereRol(["Instructor"]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idInstructor = AuthMiddleware::obtenerUsuarioId();

$errores = [];
$mensaje = null;
$categorias = [];
$valores = [
    'titulo' => '',
    'descripcion' => '',
    'categoria' => '',
    'nivel' => 'Principiante',
    'precio' => '0.00',
    'modulos' => []
];

$videoGuardado = null;

try {

    $database = new Database();
    $db = $database->conectar();

    $cursoModel = new Curso($db);

    // Obtener categorías

    $categorias = $cursoModel->obtenerCategorias();

    // Token CSRF

    if (empty($_SESSION['csrf_curso'])) {
        $_SESSION['csrf_curso'] = bin2hex(random_bytes(32));
    }

    $csrfToken = $_SESSION['csrf_curso'];

    // ======================================
    // PROCESAR FORMULARIO
    // ======================================

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $valores['titulo'] = trim($_POST['titulo'] ?? '');

        $valores['descripcion'] = trim(
            $_POST['descripcion'] ?? ''
        );

        $valores['categoria'] = $_POST['categoria'] ?? '';

        $valores['nivel'] = $_POST['nivel'] ?? '';

        $valores['precio'] = $_POST['precio'] ?? '';

        $valores['modulos'] = $_POST['modulos'] ?? [];

        // Validar CSRF

        if (
            !hash_equals(
                $_SESSION['csrf_curso'],
                $_POST['csrf_token'] ?? ''
            )
        ) {
            $errores[] = "La solicitud no es válida. Recarga la página.";
        }

        // Validar título

        if (
            $valores['titulo'] === '' ||
            mb_strlen($valores['titulo']) > 200
        ) {
            $errores[] = "El título es obligatorio y no debe superar 200 caracteres.";
        }

        // Validar descripción

        if ($valores['descripcion'] === '') {
            $errores[] = "La descripción es obligatoria.";
        }

        // Validar categoría

        $categoriaValida = false;

        foreach ($categorias as $categoria) {
            if (
                (string)$categoria['id_categoria'] ===
                (string)$valores['categoria']
            ) {
                $categoriaValida = true;
                break;
            }
        }

        if (!$categoriaValida) {
            $errores[] = "Selecciona una categoría válida.";
        }

        // Validar nivel

        $nivelesPermitidos = [
            'Principiante',
            'Intermedio',
            'Avanzado'
        ];

        if (!in_array($valores['nivel'], $nivelesPermitidos, true)) {
            $errores[] = "Selecciona un nivel válido.";
        }

        // Validar precio

        if (
            !is_numeric($valores['precio']) ||
            !is_finite((float)$valores['precio']) ||
            (float)$valores['precio'] < 0 ||
            (float)$valores['precio'] > 99999999.99
        ) {
            $errores[] = "El precio debe ser un número válido.";
        }

        // Validar módulos

        $modulosLimpios = [];

        if (
            !is_array($valores['modulos']) ||
            count($valores['modulos']) < 1 ||
            count($valores['modulos']) > 50
        ) {
            $errores[] = "Agrega al menos un módulo.";
        } else {

            foreach ($valores['modulos'] as $modulo) {

                if (!is_array($modulo)) {
                    $errores[] = "La estructura de los módulos no es válida.";
                    break;
                }

                $tituloModulo = trim($modulo['titulo'] ?? '');

                if (
                    $tituloModulo === '' ||
                    mb_strlen($tituloModulo) > 200
                ) {
                    $errores[] = "Todos los módulos deben tener un título válido.";
                    break;
                }

                $leccionesRecibidas = $modulo['lecciones'] ?? [];

                if (
                    !is_array($leccionesRecibidas) ||
                    count($leccionesRecibidas) < 1 ||
                    count($leccionesRecibidas) > 100
                ) {
                    $errores[] = "Cada módulo debe tener al menos una lección.";
                    break;
                }

                $leccionesLimpias = [];

                foreach ($leccionesRecibidas as $leccion) {

                    if (!is_string($leccion)) {
                        $errores[] = "Una lección contiene datos inválidos.";
                        break 2;
                    }

                    $leccion = trim($leccion);

                    if (
                        $leccion === '' ||
                        mb_strlen($leccion) > 200
                    ) {
                        $errores[] = "Todas las lecciones deben tener un título válido.";
                        break 2;
                    }

                    $leccionesLimpias[] = $leccion;
                }

                $modulosLimpios[] = [
                    'titulo' => $tituloModulo,
                    'lecciones' => $leccionesLimpias
                ];
            }
        }

        
        // ======================================
        // PROCESAR VIDEO OBLIGATORIO
        // ======================================

        $videoUrl = null;

        if (!isset($_FILES['video'])) {

            $errores[] = "Debes seleccionar un video para el curso.";

        } else {

            $archivo = $_FILES['video'];

            if ($archivo['error'] === UPLOAD_ERR_NO_FILE) {

                $errores[] = "El video de presentación es obligatorio.";

            } elseif ($archivo['error'] !== UPLOAD_ERR_OK) {

                $errores[] = "Ocurrió un error al subir el video.";

            } elseif ($archivo['size'] > 100 * 1024 * 1024) {

                $errores[] = "El video no puede superar los 100 MB.";

            } else {

                $finfo = new finfo(FILEINFO_MIME_TYPE);

                $mime = $finfo->file($archivo['tmp_name']);

                $tiposPermitidos = [
                    'video/mp4' => 'mp4',
                    'video/webm' => 'webm'
                ];

                if (!isset($tiposPermitidos[$mime])) {

                    $errores[] = "El video debe estar en formato MP4 o WEBM.";

                } else {

                    $directorio = __DIR__ . "/../uploads/cursos/videos/";

                    if (
                        !is_dir($directorio) &&
                        !mkdir($directorio, 0755, true)
                    ) {

                        $errores[] = "No se pudo crear el directorio de videos.";

                    } else {

                        $nombreArchivo = bin2hex(random_bytes(16))
                            . '.'
                            . $tiposPermitidos[$mime];

                        $destino = $directorio . $nombreArchivo;

                        if (
                            move_uploaded_file(
                                $archivo['tmp_name'],
                                $destino
                            )
                        ) {

                            $videoGuardado = $destino;

                            $videoUrl = url(
                                'uploads/cursos/videos/' . $nombreArchivo
                            );

                        } else {

                            $errores[] = "No se pudo guardar el video.";

                        }
                    }
                }
            }
        }



        // ======================================
        // GUARDAR CURSO
        // ======================================

        if (empty($errores)) {

            try {

                $idCurso = $cursoModel->crearCurso(
                    [
                        'titulo' => $valores['titulo'],
                        'descripcion' => $valores['descripcion'],
                        'categoria' => (int)$valores['categoria'],
                        'nivel' => $valores['nivel'],
                        'precio' => number_format(
                            (float)$valores['precio'],
                            2,
                            '.',
                            ''
                        ),
                        'video' => $videoUrl,
                        'modulos' => $modulosLimpios
                    ],
                    $idInstructor
                );

                unset($_SESSION['csrf_curso']);

                header(
                    "Location: " .
                    url('Controller/CursoController.php?creado=1')
                );

                exit;

            } catch (Throwable $e) {
                if ($videoGuardado && is_file($videoGuardado)) {
                    unlink($videoGuardado);
                }

                throw $e;
            }
        }
    }

    // ======================================
    // DATOS DEL USUARIO
    // ======================================

    $sql = "
        SELECT
            u.id_usuario,
            u.nombre,
            u.apellidos,
            u.email,
            u.foto,
            r.nombre_rol
        FROM usuarios u
        INNER JOIN roles r ON r.id_rol = u.id_rol
        WHERE u.id_usuario = :id
          AND u.estatus = 'Activo'
        LIMIT 1
    ";

    $stmt = $db->prepare($sql);

    $stmt->execute(['id' => $idInstructor]);

    $usuarioDB = $stmt->fetch();

    if (!$usuarioDB || $usuarioDB['nombre_rol'] !== 'Instructor') {
        http_response_code(403);
        exit("No tienes permisos para crear cursos.");
    }

    $datos = [
        'usuario' => [
            'id' => $idInstructor,
            'nombre' => $usuarioDB['nombre'],
            'apellido' => $usuarioDB['apellidos'],
            'correo' => $usuarioDB['email'],
            'foto' => $usuarioDB['foto'],
            'rol' => $usuarioDB['nombre_rol']
        ]
    ];

    if (isset($_GET['creado'])) {
        $mensaje = "¡Tu curso se ha creado correctamente!";
    }

    require __DIR__ . "/../View/Instructor/CreacionCurso.php";

} catch (Throwable $e) {

    error_log("Error en CursoController: " . $e->getMessage());

    http_response_code(500);

    exit("No se pudo procesar la creación del curso. Revisa el registro de errores.");
}
