<?php

namespace App;

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class WebhookHandler
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(MERCADOPAGO_ACCESS_TOKEN);
    }

    public function handleWebhook($data)
    {
        error_log("Webhook recibido. Datos: " . print_r($data, true));

        $client = new PaymentClient();
        $pago = new Pago();

        try {
            // Obtener detalles del pago desde MercadoPago
            $payment = $client->get($data['id']);
            error_log("Detalles del pago obtenidos: " . print_r($payment, true));

            $planId = $this->extractPlanId($payment->external_reference);
            if ($planId) {
                error_log("Plan ID extraído: " . $planId);

                if ($payment->status === 'approved') {
                    error_log("Estado del pago aprobado. Procediendo a registrar en la base de datos...");

                    // Registrar el pago junto con los datos del comprador desde el webhook
                    $payerFirstName = $payment->payer->first_name ?? 'No especificado';
                    $payerLastName = $payment->payer->last_name ?? 'No especificado';
                    $payerEmail = $payment->payer->email ?? 'No especificado';

                    $result = $pago->registrarPago(
                        $planId,
                        $payment->id,
                        $payment->status,
                        $payment->transaction_amount,
                        $payment->currency_id,
                        $payerEmail,
                        $payerFirstName,
                        $payerLastName
                    );

                    if ($result) {
                        error_log("Pago registrado exitosamente en la base de datos.");
                    } else {
                        error_log("Error: No se pudo registrar el pago en la base de datos.");
                    }
                } else {
                    error_log("El estado del pago no es 'approved'. Estado recibido: " . $payment->status);
                }
            } else {
                error_log("Error: ID del plan no encontrado en la referencia externa.");
            }
        } catch (MPApiException $e) {
            error_log("Error API MercadoPago: " . $e->getApiResponse()->getContent());
        } catch (\Exception $e) {
            error_log("Error procesando webhook: " . $e->getMessage());
        }
    }

    private function extractPlanId($reference)
    {
        preg_match('/Plan-(\d+)/', $reference, $matches);
        return $matches[1] ?? null;
    }
}
