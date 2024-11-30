<?php
// pago_exitoso.php

require_once 'vendor/autoload.php';

use App\Log; // Asumiendo que tienes una clase de Log para manejar eventos de pago

// Definir el archivo de log personalizado
ini_set("log_errors", 1);
ini_set("error_log", "/var/log/nginx/php_pago_exitoso.log");

// Log de pago exitoso (opcional)
error_log("El usuario ha realizado un pago exitoso en ReserGo");

// Página HTML
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<base href="" />
	<title>Pago Exitoso - ReserGo Uruguay</title>
	<meta charset="utf-8" />
	<meta name="description" content="Gracias por tu pago. Tu transacción ha sido completada exitosamente en ReserGo Uruguay." />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<!-- Favicons -->
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
	<link rel="manifest" href="./site.webmanifest">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#4F46E5">
</head>

<body id="kt_body" class="app-blank">
	<script>
		var themeMode = "dark";
		if (document.documentElement) {
			document.documentElement.setAttribute("data-bs-theme", themeMode);
		}
	</script>
	<div class="scroll-y flex-column-fluid px-10 py-10" data-kt-scroll="true" style="background-color: rgb(213, 217, 226);">
		<div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
				<tbody>
					<tr>
						<td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
							<div style="text-align:center; margin:0 60px 34px 60px">
								<img alt="Éxito" src="https://preview.keenthemes.com/metronic8/demo1/assets/media/email/icon-positive-vote-4.svg" style="margin-bottom: 15px;">
								<div style="font-size: 14px; font-family:Arial,Helvetica,sans-serif;">
									<p style="color:#181C32; font-size: 22px; font-weight:700; margin-bottom: 10px;">¡Pago Exitoso!</p>
									<p style="color:#7E8299; margin-bottom: 30px;">Tu pago ha sido procesado satisfactoriamente.</p>
									<p style="color:#5E6278; margin-bottom: 20px;">Gracias por confiar en ReserGo Uruguay. Estamos listos para ayudarte a gestionar tu barbería de la mejor manera.</p>
									<p style="color:#5E6278; margin-bottom: 30px;">Para cualquier consulta, contáctanos:</p>
									<p><a href="mailto:hola@resergo.uy" style="color:#7e3af2; font-weight:600; text-decoration: none;">hola@resergo.uy</a></p>
								</div>
								<a href="https://www.resergo.uy" target="_blank" style="display:inline-block; background-color:#7e3af2; border-radius:6px; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500; font-family:Arial,Helvetica,sans-serif; text-decoration:none; margin-top: 20px;">
									Volver al sitio
								</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>

</html>