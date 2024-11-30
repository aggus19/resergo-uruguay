<?php

namespace App;

class Pago
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        error_log("Conexión a la base de datos establecida en Pago.");
    }

    public function registrarPago($planId, $paymentId, $status, $amount, $currency, $payerEmail, $payerFirstName, $payerLastName): bool
    {
        error_log("Intentando registrar el pago en la base de datos...");
        $stmt = $this->db->prepare(
            "INSERT INTO pagos (plan_id, payment_id, status, amount, currency, payer_email, payer_first_name, payer_last_name)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $result = $stmt->execute([$planId, $paymentId, $status, $amount, $currency, $payerEmail, $payerFirstName, $payerLastName]);

        if ($result) {
            error_log("Pago registrado en la base de datos: payment_id = $paymentId, plan_id = $planId, status = $status");
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("Error al registrar el pago en la base de datos: " . print_r($errorInfo, true));
        }

        return $result;
    }
}
