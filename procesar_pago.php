<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use App\Plan;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

MercadoPagoConfig::setAccessToken(MERCADOPAGO_ACCESS_TOKEN);

if (!isset($_GET['plan_id'])) {
    error_log("Error: plan_id no especificado en la solicitud.");
    die("Error: Plan no especificado.");
}

$planId = (int)$_GET['plan_id'];
$plan = new Plan();
$planData = $plan->getPlanById($planId);

if (!$planData) {
    error_log("Error: No se encontró el plan con ID " . $planId);
    die("Error: Plan no encontrado.");
}

$items = [
    [
        "id" => "Plan-" . $planId,
        "title" => $planData['nombre'] . " - ReserGo Uruguay",
        "description" => "Pago por " . $planData['nombre'] . " en ReserGo",
        "quantity" => 1,
        "unit_price" => (float) $planData['precio_uyu'],
        "currency_id" => "UYU",
        "category_id" => "services"
    ]
];

$request = [
    "items" => $items,
    "payment_methods" => [
        "installments" => 12
    ],
    "back_urls" => [
        "success" => SUCCESS_URL,
        "failure" => FAILURE_URL,
        "pending" => PENDING_URL
    ],
    "auto_return" => "approved",
    "statement_descriptor" => "ReserGoUY",
    "external_reference" => "Plan-$planId",
    "notification_url" => NOTIFICATION_URL
];

$client = new PreferenceClient();
try {
    $preference = $client->create($request);
    error_log("Preferencia de pago creada exitosamente. URL de Checkout: " . $preference->init_point);
} catch (Exception $e) {
    error_log("Error al crear la preferencia de pago: " . $e->getMessage());
    die("Error al procesar el pago. Intente nuevamente.");
}

// Redirigir al usuario a la página de pago
header("Location: " . $preference->init_point);
exit;
