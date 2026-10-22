
<?php

class Pago
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearPendiente(
        int $idEstudiante,
        int $idCurso,
        float $monto,
        string $sessionId
    ): void {
        $sql = "INSERT INTO pagos (
                    id_estudiante,
                    id_curso,
                    monto,
                    metodo_pago,
                    estado_pago,
                    referencia_pago
                ) VALUES (
                    :estudiante,
                    :curso,
                    :monto,
                    'Stripe Checkout',
                    'Pendiente',
                    :referencia
                )";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            'estudiante' => $idEstudiante,
            'curso' => $idCurso,
            'monto' => number_format($monto, 2, '.', ''),
            'referencia' => $sessionId
        ]);
    }

    /**
     * Confirma un pago e inscribe al estudiante.
     * Debe llamarse solo después de validar el webhook
     * y verificar que Stripe confirma el pago.
     */
    public function confirmarPago(
        string $sessionId,
        int $idEstudiante,
        int $idCurso,
        int $importeCentavos,
        string $moneda
    ): void {
        if (
            $sessionId === ''
            || $idEstudiante <= 0
            || $idCurso <= 0
            || $importeCentavos <= 0
            || strtolower($moneda) !== 'mxn'
        ) {
            throw new RuntimeException(
                'Los datos del pago no son válidos.'
            );
        }

        $this->conexion->beginTransaction();

        try {
            // Bloquear el pago para evitar dos confirmaciones
            // concurrentes de la misma sesión.
            $sql = "SELECT id_pago, id_estudiante, id_curso,
                           monto, estado_pago
                    FROM pagos
                    WHERE referencia_pago = :referencia
                    LIMIT 1
                    FOR UPDATE";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute(['referencia' => $sessionId]);

            $pago = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pago) {
                throw new RuntimeException(
                    'No existe un pago asociado a esta sesión.'
                );
            }

            if (
                (int) $pago['id_estudiante'] !== $idEstudiante
                || (int) $pago['id_curso'] !== $idCurso
            ) {
                throw new RuntimeException(
                    'La sesión no corresponde al estudiante y curso.'
                );
            }

            // Comparar en centavos para evitar errores de flotantes.
            $montoRegistrado = (int) round(
                (float) $pago['monto'] * 100
            );

            if ($montoRegistrado !== $importeCentavos) {
                throw new RuntimeException(
                    'El importe confirmado no coincide con el pago.'
                );
            }

            if ($pago['estado_pago'] === 'Completado') {
                // Un evento repetido no debe duplicar la inscripción.
                $this->conexion->commit();
                return;
            }

            if ($pago['estado_pago'] !== 'Pendiente') {
                throw new RuntimeException(
                    'El pago no se encuentra pendiente.'
                );
            }

            $actualizar = $this->conexion->prepare(
                "UPDATE pagos
                 SET estado_pago = 'Completado'
                 WHERE id_pago = :id_pago
                   AND estado_pago = 'Pendiente'"
            );

            $actualizar->execute([
                'id_pago' => $pago['id_pago']
            ]);

            // La restricción UNIQUE de inscripciones impide duplicados.
            $inscripcion = $this->conexion->prepare(
                "INSERT INTO inscripciones (
                    id_estudiante,
                    id_curso,
                    estatus,
                    progreso
                ) VALUES (
                    :estudiante,
                    :curso,
                    'Activa',
                    0
                )
                ON DUPLICATE KEY UPDATE
                    id_inscripcion = id_inscripcion"
            );

            $inscripcion->execute([
                'estudiante' => $idEstudiante,
                'curso' => $idCurso
            ]);

            $this->conexion->commit();

        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

    public function marcarFallido(string $sessionId): void
    {
        $sql = "UPDATE pagos
                SET estado_pago = 'Fallido'
                WHERE referencia_pago = :referencia
                  AND estado_pago = 'Pendiente'";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['referencia' => $sessionId]);
    }
}
