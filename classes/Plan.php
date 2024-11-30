<?php

namespace App;

use PDO;

class Plan
{
    private $db;

    public function __construct()
    {
        error_log("Inicializando clase Plan y obteniendo conexión de la base de datos...");
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPlans()
    {
        error_log("Ejecutando getAllPlans en la clase Plan...");
        $sql = "
            SELECT p.id, p.nombre, p.descripcion, p.precio_usd, p.precio_uyu, p.mercadopago_link, 
                   p.is_discounted, p.discount_percentage, p.is_popular, f.feature_text
            FROM planes p
            LEFT JOIN plan_features f ON p.id = f.plan_id
            ORDER BY p.id, f.id;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $planes = [];
        $currentPlanId = null;
        $currentPlan = null;

        foreach ($result as $row) {
            if ($currentPlanId !== $row['id']) {
                if ($currentPlan !== null) {
                    // Agregar un array vacío para "features" si no existen características
                    if (!isset($currentPlan['features'])) {
                        $currentPlan['features'] = [];
                    }
                    $planes[] = $currentPlan;
                }
                $currentPlanId = $row['id'];
                $currentPlan = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'descripcion' => $row['descripcion'],
                    'precio_usd' => $row['precio_usd'],
                    'precio_uyu' => $row['precio_uyu'],
                    'mercadopago_link' => $row['mercadopago_link'],
                    'is_discounted' => $row['is_discounted'],
                    'discount_percentage' => $row['discount_percentage'],
                    'is_popular' => $row['is_popular'],
                    'features' => [], // Inicializar "features" como un array vacío
                ];
            }
            if ($row['feature_text'] !== null) {
                $currentPlan['features'][] = $row['feature_text'];
            }
        }

        // Asegurarse de agregar el último plan en el array si existe
        if ($currentPlan !== null) {
            if (!isset($currentPlan['features'])) {
                $currentPlan['features'] = [];
            }
            $planes[] = $currentPlan;
        }

        // error_log("Resultado de getAllPlans: " . print_r($planes, true));
        return $planes;
    }

    public function getPlanById($planId)
    {
        error_log("Ejecutando getPlanById en la clase Plan para plan_id: $planId");
        $sql = "SELECT id, nombre, descripcion, precio_usd, precio_uyu FROM planes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$planId]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($plan) {
            error_log("Plan encontrado: " . print_r($plan, true));
        } else {
            error_log("No se encontró el plan con ID $planId");
        }

        return $plan;
    }
}
