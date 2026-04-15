<?php

namespace App\Models;

use PDO;

class ClientModel
{
    private PDO $conexionBd;

    public function __construct()
    {
        $this->conexionBd = Database::getInstancia()->getConexion();
    }

    public function obtenerTodos(): array
    {
        $sql = "SELECT * FROM clientes ORDER BY id DESC";
        $stmt = $this->conexionBd->query($sql);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $this->conexionBd->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function insertar(array $datos): int
    {
        $sql = "INSERT INTO clientes
            (cif, nombre, telefono, correo, cuenta_corriente, pais, moneda, importe_cuota_mensual)
            VALUES
            (:cif, :nombre, :telefono, :correo, :cuenta_corriente, :pais, :moneda, :importe_cuota_mensual)";

        $stmt = $this->conexionBd->prepare($sql);
        $stmt->execute([
            'cif'                    => $datos['cif'],
            'nombre'                 => $datos['nombre'],
            'telefono'               => $datos['telefono'],
            'correo'                 => $datos['correo'],
            'cuenta_corriente'       => $datos['cuenta_corriente'],
            'pais'                   => $datos['pais'],
            'moneda'                 => $datos['moneda'],
            'importe_cuota_mensual'  => $datos['importe_cuota_mensual'],
        ]);

        return (int)$this->conexionBd->lastInsertId();
    }

    public function actualizar(int $id, array $datos): void
    {
        $sql = "UPDATE clientes SET
            cif = :cif,
            nombre = :nombre,
            telefono = :telefono,
            correo = :correo,
            cuenta_corriente = :cuenta_corriente,
            pais = :pais,
            moneda = :moneda,
            importe_cuota_mensual = :importe_cuota_mensual
            WHERE id = :id";

        $stmt = $this->conexionBd->prepare($sql);
        $stmt->execute([
            'cif'                    => $datos['cif'],
            'nombre'                 => $datos['nombre'],
            'telefono'               => $datos['telefono'],
            'correo'                 => $datos['correo'],
            'cuenta_corriente'       => $datos['cuenta_corriente'],
            'pais'                   => $datos['pais'],
            'moneda'                 => $datos['moneda'],
            'importe_cuota_mensual'  => $datos['importe_cuota_mensual'],
            'id'                     => $id,
        ]);
    }

    public function eliminar(int $id): void
    {
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $this->conexionBd->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
