<?php

namespace App\Models;

use PDO;
use App\Models\Database;

class TaskModel
{
    private PDO $db;

    public function __construct()
    {
        // Usamos el Singleton Database (debe estar también en App\Models)
        $this->db = Database::getInstancia()->getConexion();
    }

    public function obtenerTodas(): array
    {
        $sql = "SELECT * FROM tareas ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM tareas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function insertar(array $datos): int
    {
        $sql = "INSERT INTO tareas
            (contacto, nif, telefono, email, direccion, poblacion, cp, provincia,
             descripcion, fecha, operario, anot_prev, anot_post, estado, fecha_creacion, fichero)
            VALUES
            (:contacto, :nif, :telefono, :email, :direccion, :poblacion, :cp, :provincia,
             :descripcion, :fecha, :operario, :anot_prev, :anot_post, :estado, :fecha_creacion, :fichero)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'contacto'       => $datos['contacto'],
            'nif'            => $datos['nif'],
            'telefono'       => $datos['telefono'],
            'email'          => $datos['email'],
            'direccion'      => $datos['direccion'],
            'poblacion'      => $datos['poblacion'],
            'cp'             => $datos['cp'],
            'provincia'      => $datos['provincia'],
            'descripcion'    => $datos['descripcion'],
            'fecha'          => $datos['fecha'],
            'operario'       => $datos['operario'],
            'anot_prev'      => $datos['anot_prev'],
            'anot_post'      => $datos['anot_post'],
            'estado'         => $datos['estado'],
            'fecha_creacion' => $datos['fecha_creacion'],
            'fichero'        => $datos['fichero'] ?? '',
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function actualizarDesdeOperario(int $id, array $datos): void
    {
        $sql = "UPDATE tareas SET
            fecha = :fecha,
            anot_post = :anot_post,
            estado = :estado
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fecha'     => $datos['fecha'],
            'anot_post' => $datos['anot_post'],
            'estado'    => $datos['estado'],
            'id'        => $id,
        ]);
    }
    
    public function eliminar(int $id): void
{
    $sql = "DELETE FROM tareas WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
}

public function obtenerPorEstado(string $estado): array
{
    if ($estado === '') {
        // Si no hay filtro, devolvemos todas
        return $this->obtenerTodas();
    }

    $sql = "SELECT * FROM tareas WHERE estado = :estado ORDER BY id DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerPorOperario(int $operarioId): array
    {
        $sql = "SELECT * FROM tareas WHERE operario = :operario ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['operario' => $operarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorOperarioYEstado(int $operarioId, string $estado): array
    {
        if ($estado === '') {
            return $this->obtenerPorOperario($operarioId);
        }

        $sql = "SELECT * FROM tareas WHERE operario = :operario AND estado = :estado ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['operario' => $operarioId, 'estado' => $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
