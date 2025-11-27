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
        if ($fila === false) {
            return null;
        }
        return $fila;
    }
}
