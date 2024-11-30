<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'barberias');
define('DB_USER', 'local_user');
define('DB_PASS', '');

// Configuración de MercadoPago
define('MERCADOPAGO_ACCESS_TOKEN', '');
define('WEBHOOK_SECRET_KEY', '');

// URLs de retorno y notificación
define('NOTIFICATION_URL', 'https://resergo.uy/webhook');
define('SUCCESS_URL', 'https://resergo.uy/pago_exitoso');
define('FAILURE_URL', 'https://resergo.uy/pago_fallido');
define('PENDING_URL', 'https://resergo.uy/pago_pendiente');
