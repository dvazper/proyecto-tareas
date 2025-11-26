<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instancia = null;
    private PDO $conexion;

    private function __construct()
    {
        // Lee la configuración desde config/bd.php
        $config = require base_path('config/bd.php');

        $dsn  = $config['dsn']  ?? '';
        $user = $config['user'] ?? '';
        $pass = $config['pass'] ?? '';

        try {
            $this->conexion = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // muestra el error
            die('Error de conexión a la BD: ' . $e->getMessage());
        }
    }

    public static function getInstancia(): Database
    {
        if (self::$instancia === null) {
            self::$instancia = new Database();
        }
        return self::$instancia;
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}
