<?php

namespace App;

use PDO;
use Exception;

class Auth
{
    private $db;

    private const DEMO_BARBERIA_NOMBRE = "Barbería de Demostración";
    private const DEMO_SUCURSAL_NOMBRE = "Sucursal de Demostración";
    private const DEMO_SUCURSAL_DIRECCION = "Dirección de Demostración";
    private const DIAS_SEMANA = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register(string $nombre, string $apellido, string $email, string $telefono, string $password, string $nombreBarberia): bool
    {
        if (strlen($telefono) > 20) {
            error_log("Error: El número de teléfono es demasiado largo.");
            return false;
        }

        try {
            $this->db->beginTransaction();

            // Crear usuario
            $userId = $this->createUser($nombre, $apellido, $email, $telefono, $password);

            // Generar slug
            $slug = $this->generateSlug($nombreBarberia);

            // Verificar si el slug ya existe
            if ($this->isSlugExists($slug)) {
                error_log("Error: El nombre de la barbería ya existe.");
                $this->db->rollBack();
                return false;
            }

            // Crear barbería con el nombre y slug proporcionado
            $barberiaId = $this->createDemoBarberia($nombreBarberia, $telefono, $slug);
            $sucursalId = $this->createDemoSucursal($barberiaId, $telefono);

            // Crear datos de ejemplo
            $this->createDemoData($sucursalId);

            // Actualizar el usuario con el ID de la sucursal creada
            $this->updateUserSucursal($userId, $sucursalId);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al registrar usuario y crear demo: " . $e->getMessage());
            return false;
        }
    }

    private function generateSlug(string $nombreBarberia): string
    {
        if (strpos($nombreBarberia, ' ') === false) {
            return "barberia-" . strtolower($nombreBarberia);
        } else {
            return strtolower(str_replace(' ', '-', $nombreBarberia));
        }
    }

    private function isSlugExists(string $slug): bool
    {
        $query = $this->db->prepare("
            SELECT COUNT(*) FROM barberias WHERE slug = :slug
        ");
        $query->execute(['slug' => $slug]);
        return $query->fetchColumn() > 0;
    }

    private function createUser(string $nombre, string $apellido, string $email, string $telefono, string $password): int
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = $this->db->prepare("
            INSERT INTO usuarios (nombre, apellido, email, telefono, password, rol) 
            VALUES (:nombre, :apellido, :email, :telefono, :password, 'Dueño')
        ");
        $query->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => $telefono,
            'password' => $hashedPassword,
        ]);
        return $this->db->lastInsertId();
    }

    private function createDemoBarberia(string $nombreBarberia, string $telefono, string $slug): int
    {
        $query = $this->db->prepare("
            INSERT INTO barberias (nombre, telefono, email, sitio_web, descripcion, activa, membresia, slug) 
            VALUES (:nombreBarberia, :telefono, 'temp@resergo.uy', 'www.demo-barberia.com', 'Descripcion de ejemplo', 1, 'Gratis', :slug)
        ");
        $query->execute([
            'nombreBarberia' => $nombreBarberia,
            'telefono' => $telefono,
            'slug' => $slug,
        ]);
        $barberiaId = $this->db->lastInsertId();

        // Lógica para generar el email
        $emailBarberia = "demo{$barberiaId}-" . strtolower(str_replace(' ', '', $nombreBarberia)) . "@resergo.uy";
        $this->updateBarberiaSlugAndEmail($barberiaId, $emailBarberia, $slug);
        return $barberiaId;
    }

    private function updateBarberiaSlugAndEmail(int $barberiaId, string $email, string $slug): void
    {
        $query = $this->db->prepare("
            UPDATE barberias SET email = :email, slug = :slug WHERE id = :barberiaId
        ");
        $query->execute([
            'email' => $email,
            'slug' => $slug,
            'barberiaId' => $barberiaId,
        ]);
    }
    private function createDemoSucursal(int $barberiaId, string $telefono): int
    {
        $query = $this->db->prepare("
            INSERT INTO sucursales (barberia_id, nombre, direccion, email, telefono, activa) 
            VALUES (:barberiaId, :nombre, :direccion, 'demo-sucursal@resergo.uy', :telefono, 1)
        ");
        $query->execute([
            'barberiaId' => $barberiaId,
            'nombre' => self::DEMO_SUCURSAL_NOMBRE,
            'direccion' => self::DEMO_SUCURSAL_DIRECCION,
            'telefono' => $telefono,
        ]);
        return $this->db->lastInsertId();
    }

    private function createDemoData(int $sucursalId): void
    {
        $barberoId = $this->createDemoBarbero($sucursalId);
        $this->createDemoHorariosBarbero($barberoId, $sucursalId);
        $this->createDemoCliente($sucursalId);
        $this->createDemoHorariosSucursal($sucursalId);
        $this->createDemoMetodoPago($sucursalId);
        $servicioId = $this->createDemoServicio($sucursalId);
        $this->createDemoServicioBarbero($barberoId, $servicioId, $sucursalId);
    }

    private function createDemoBarbero(int $sucursalId): int
    {
        $query = $this->db->prepare("
            INSERT INTO barberos (sucursal_id, nombre, apellido, celular, email, activo) 
            VALUES (:sucursalId, 'Juan', 'Pérez', '1234567890', 'juan.perez@demo.com', 1)
        ");
        $query->execute([
            'sucursalId' => $sucursalId,
        ]);
        return $this->db->lastInsertId();
    }

    private function createDemoHorariosBarbero(int $barberoId, int $sucursalId): void
    {
        foreach (self::DIAS_SEMANA as $dia) {
            $query = $this->db->prepare("
                INSERT INTO horarios_barberos (barbero_id, sucursal_id, dia, hora_inicio, hora_fin) 
                VALUES (:barberoId, :sucursalId, :dia, '10:00:00', '18:00:00')
            ");
            $query->execute([
                'barberoId' => $barberoId,
                'sucursalId' => $sucursalId,
                'dia' => $dia,
            ]);
        }
    }

    private function createDemoCliente(int $sucursalId): void
    {
        $emailCliente = "cliente.demo{$sucursalId}@demo.com";
        $query = $this->db->prepare("
            INSERT INTO clientes (sucursal_id, nombre, apellido, email, telefono, activo) 
            VALUES (:sucursalId, 'Cliente', 'Demo', :email, '0987654321', 1)
        ");
        $query->execute([
            'sucursalId' => $sucursalId,
            'email' => $emailCliente,
        ]);
    }

    private function createDemoHorariosSucursal(int $sucursalId): void
    {
        foreach (self::DIAS_SEMANA as $dia) {
            $query = $this->db->prepare("
                INSERT INTO horarios_sucursales (sucursal_id, dia, hora_apertura, hora_cierre, estado) 
                VALUES (:sucursalId, :dia, '09:00:00', '19:00:00', 1)
            ");
            $query->execute([
                'sucursalId' => $sucursalId,
                'dia' => $dia,
            ]);
        }
    }

    private function createDemoMetodoPago(int $sucursalId): void
    {
        $query = $this->db->prepare("
            INSERT INTO metodos_pago (sucursal_id, nombre, descripcion, activo) 
            VALUES (:sucursalId, 'Efectivo', 'Pago en efectivo', 1)
        ");
        $query->execute([
            'sucursalId' => $sucursalId,
        ]);
    }

    private function createDemoServicio(int $sucursalId): int
    {
        $query = $this->db->prepare("
            INSERT INTO servicios (sucursal_id, nombre, descripcion, duracion, precio, activo) 
            VALUES (:sucursalId, 'Corte de Cabello', 'Corte de cabello clásico', 30, 15.00, 1)
        ");
        $query->execute([
            'sucursalId' => $sucursalId,
        ]);
        return $this->db->lastInsertId();
    }

    private function createDemoServicioBarbero(int $barberoId, int $servicioId, int $sucursalId): void
    {
        $query = $this->db->prepare("
            INSERT INTO barberos_servicios (barbero_id, servicio_id, sucursal_id) 
            VALUES (:barberoId, :servicioId, :sucursalId)
        ");
        $query->execute([
            'barberoId' => $barberoId,
            'servicioId' => $servicioId,
            'sucursalId' => $sucursalId,
        ]);
    }

    private function updateUserSucursal(int $userId, int $sucursalId): void
    {
        $query = $this->db->prepare("
            UPDATE usuarios SET sucursal_id = :sucursalId WHERE id = :userId
        ");
        $query->execute([
            'sucursalId' => $sucursalId,
            'userId' => $userId,
        ]);
    }

    public function isEmailRegistered(string $email): bool
    {
        $query = $this->db->prepare("
            SELECT COUNT(*) FROM usuarios WHERE email = :email
        ");
        $query->execute([
            'email' => $email,
        ]);
        return $query->fetchColumn() > 0;
    }
}
