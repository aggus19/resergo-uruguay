<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\WebhookHandler;

define("SECRET_KEY", WEBHOOK_SECRET_KEY);

$requestContent = file_get_contents("php://input");
$data = json_decode($requestContent, true);
$headers = getallheaders();
$signature = $headers['x-mp-signature'] ?? null;

if ($signature !== hash_hmac('sha256', $requestContent, SECRET_KEY)) {
    http_response_code(400);
    die("Firma inválida");
}

if (isset($data['id']) && $data['type'] === 'payment') {
    $webhookHandler = new WebhookHandler();
    $webhookHandler->handleWebhook($data);
}

http_response_code(200);
