<?php

namespace App;

use PDO;
use PDOException;

// Incluye la configuración
require_once __DIR__ . '/../config.php';

class Database
{
  private static $instance = null;
  private $connection;

  private function __construct()
  {
    error_log("Inicializando conexión de base de datos...");
    $this->conectar();
  }

  public static function getInstance(): self
  {
    if (self::$instance === null) {
      error_log("Creando nueva instancia de Database...");
      self::$instance = new self();
    } else {
      error_log("Usando instancia existente de Database...");
    }
    return self::$instance;
  }

  private function conectar(): void
  {
    // Log de las constantes de configuración para verificar sus valores
    error_log("Configuración de la conexión a la base de datos:");
    error_log("DB_HOST: " . DB_HOST);
    error_log("DB_NAME: " . DB_NAME);
    error_log("DB_USER: " . DB_USER);
    error_log("DB_PASS: " . DB_PASS);

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    try {
      // Intento de conexión
      error_log("Intentando conectar a la base de datos con DSN: $dsn y usuario: " . DB_USER . " y contraseña: " . DB_PASS);
      $this->connection = new PDO($dsn, DB_USER, DB_PASS);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      error_log("Conexión a la base de datos establecida correctamente.");
    } catch (PDOException $e) {
      // Registro detallado en caso de error
      error_log("Error de conexión a la base de datos: " . $e->getMessage());
      throw new PDOException('No se pudo establecer la conexión a la base de datos.');
    }
  }

  public function getConnection(): PDO
  {
    error_log("Obteniendo conexión de base de datos...");
    return $this->connection;
  }
}
