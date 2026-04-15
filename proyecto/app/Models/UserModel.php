<?php

namespace App\Models;

use PDO;

class UserModel
{
    private PDO $conexionBd;

    public function __construct()
    {
        $this->conexionBd = Database::getInstancia()->getConexion();
    }

    public function buscarPorNombreUsuario(string $nombreUsuario): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1";
        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute(['usuario' => $nombreUsuario]);

        $fila = $consulta->fetch();
        return $fila ?: null;
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute(['id' => $id]);

        $fila = $consulta->fetch();
        return $fila ?: null;
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
        $consulta = $this->conexionBd->query($sql);
        return $consulta->fetchAll();
    }

    public function existeUsuario(string $usuario, ?int $idExcluir = null): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario";
        $parametros = ['usuario' => $usuario];

        if ($idExcluir !== null) {
            $sql .= " AND id != :id";
            $parametros['id'] = $idExcluir;
        }

        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute($parametros);

        return $consulta->fetchColumn() > 0;
    }

    public function insertar(array $datos): int
    {
        $sql = "INSERT INTO usuarios
            (usuario, password_hash, rol, correo, fecha_alta)
            VALUES
            (:usuario, :password_hash, :rol, :correo, :fecha_alta)";

        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute([
            'usuario'       => $datos['usuario'],
            'password_hash' => $datos['password_hash'],
            'rol'           => $datos['rol'],
            'correo'        => $datos['correo'],
            'fecha_alta'    => $datos['fecha_alta'],
        ]);

        return (int)$this->conexionBd->lastInsertId();
    }

    public function actualizar(int $id, array $datos): void
    {
        $sql = "UPDATE usuarios SET
            usuario = :usuario,
            rol = :rol,
            correo = :correo,
            fecha_alta = :fecha_alta";

        $parametros = [
            'usuario'    => $datos['usuario'],
            'rol'        => $datos['rol'],
            'correo'     => $datos['correo'],
            'fecha_alta' => $datos['fecha_alta'],
            'id'         => $id,
        ];

        if (!empty($datos['password_hash'])) {
            $sql .= ", password_hash = :password_hash";
            $parametros['password_hash'] = $datos['password_hash'];
        }

        $sql .= " WHERE id = :id";

        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute($parametros);
    }

    public function actualizarContacto(int $id, string $correo, string $fechaAlta): void
    {
        $sql = "UPDATE usuarios SET correo = :correo, fecha_alta = :fecha_alta WHERE id = :id";
        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute([
            'correo'     => $correo,
            'fecha_alta' => $fechaAlta,
            'id'         => $id,
        ]);
    }

    public function eliminar(int $id): void
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $consulta = $this->conexionBd->prepare($sql);
        $consulta->execute(['id' => $id]);
    }
}
