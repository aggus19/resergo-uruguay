<?php
// register.php

require_once 'vendor/autoload.php';

use App\Auth;
use App\Email;

// Definir el archivo de log personalizado
ini_set("log_errors", 1);
ini_set("error_log", "/var/log/nginx/php_register_error.log");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	header('Content-Type: application/json');
	$data = json_decode(file_get_contents('php://input'), true);

	// Validación de campos
	if (!validarDatos($data)) {
		error_log("Error de validación de datos: Campos obligatorios faltantes o inválidos."); // Log de error
		responder(false, 'Todos los campos son obligatorios, el email, teléfono y contraseña deben cumplir con los requisitos.');
	}

	$auth = new Auth();

	// Verificar si el correo ya está registrado
	if ($auth->isEmailRegistered($data['email'])) {
		error_log("Error: El correo electrónico ya está registrado - " . $data['email']); // Log de error
		responder(false, 'El correo electrónico ya está registrado.');
	}

	// Registro del usuario y barbería
	$registerResult = $auth->register(
		$data['nombre'],
		$data['apellido'],
		$data['email'],
		$data['telefono'],
		$data['password'],
		$data['nombre_barberia']
	);

	if ($registerResult) {
		error_log("Registro exitoso para el usuario: " . $data['email']); // Log de éxito

		// Enviar correo de bienvenida con la contraseña
		$emailService = new Email();
		$emailSent = $emailService->enviarBienvenidaRegistro(
			$data['email'],
			$data['nombre'],
			$data['apellido'],
			$data['nombre_barberia'],
			$data['password'] // Añadir la contraseña como parámetro
		);

		if ($emailSent) {
			error_log("Correo de bienvenida enviado a: " . $data['email']); // Log de éxito
		} else {
			error_log("Error al enviar el correo de bienvenida a: " . $data['email']); // Log de error
		}
	} else {
		error_log("Error al registrar el usuario: " . $data['email']); // Log de error
	}

	// Responder según resultado
	responder($registerResult, $registerResult ? 'Registro exitoso' : 'Error al registrar la cuenta. Inténtelo nuevamente.');
}

// Validaciones generales de datos
function validarDatos($data)
{
	return isset($data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['password'], $data['nombre_barberia'])
		&& filter_var($data['email'], FILTER_VALIDATE_EMAIL)
		&& preg_match('/^\+?[0-9]{7,15}$/', $data['telefono'])
		&& strlen($data['password']) >= 8 && preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $data['password']);
}

// Responder en JSON y terminar el script
function responder($success, $message)
{
	echo json_encode(['success' => $success, 'message' => $message]);
	exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<base href="" />
	<title>¡Comienza gratis ahora mismo! - Panel de Gestión de Barbería | ReserGo</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Accede al Panel de Gestión de Barbería de ReserGo y administra tu negocio de manera eficiente. Inicia sesión para gestionar clientes, citas y servicios." />
	<meta name="keywords" content="ReserGo, barbería, gestión de barberías, software de gestión, panel de control, registrarse, login barbería, administración de barbería" />
	<meta name="robots" content="index, follow" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<!-- Open Graph para Redes Sociales -->
	<meta property="og:locale" content="es_ES" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="¡Comienza gratis ahora mismo! - Panel de Gestión de Barbería | ReserGo" />
	<meta property="og:description" content="Accede al Panel de Gestión de Barbería de ReserGo para gestionar clientes, citas y servicios. Optimiza tu negocio con nuestra plataforma." />
	<meta property="og:url" content="https://resergo.uy/registro" />
	<meta property="og:site_name" content="ReserGo" />
	<meta property="og:image" content="https://resergo.uy/assets/media/social-banner.jpg" />

	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="¡Comienza gratis ahora mismo! - Panel de Gestión de Barbería | ReserGo" />
	<meta name="twitter:description" content="Accede al Panel de Gestión de Barbería de ReserGo y administra tu negocio de manera eficiente. Inicia sesión para gestionar clientes, citas y servicios." />
	<meta name="twitter:image" content="https://resergo.uy/assets/media/social-banner.jpg" />

	<!-- Canonical -->
	<link rel="canonical" href="https://resergo.uy/registro" />

	<!-- Favicons -->
	<link rel="icon" type="image/png" sizes="32x32" href="assets/media/resergo/32x32.png" />
	<link rel="icon" type="image/png" sizes="16x16" href="assets/media/resergo/16x16.png" />
	<link rel="manifest" href="./manifest.json" />
	<meta name="msapplication-TileColor" content="#8936FF" />
	<meta name="theme-color" content="#090524" />

	<!-- SEO Local y Geolocalización -->
	<meta name="geo.region" content="UY" />
	<meta name="geo.placename" content="Uruguay" />
	<meta name="geo.position" content="-34.9011127;-56.1645314" />
	<meta name="ICBM" content="-34.9011127, -56.1645314" />

	<!-- Preconexión y Prefetch -->
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://www.googletagmanager.com" />
	<link rel="dns-prefetch" href="https://fonts.googleapis.com" />
	<link rel="dns-prefetch" href="https://www.googletagmanager.com" />

	<!-- Fuentes y Estilos -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
	<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

	<!-- JSON-LD Schema Markup -->
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "WebPage",
			"name": "¡Comienza gratis ahora mismo! - Panel de Gestión de Barbería | ReserGo",
			"description": "¡Comienza tu prueba gratita en ReserGo ahora mismo! ",
			"url": "https://resergo.uy/registro"
		}
	</script>

	<!-- Google Analytics Global Site Tag - Carga diferida -->
	<script>
		window.addEventListener('load', function() {
			var script = document.createElement('script');
			script.src = 'https://www.googletagmanager.com/gtag/js?id=G-09SEML3SBN';
			document.head.appendChild(script);

			script.onload = function() {
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}
				gtag('js', new Date());
				gtag('config', 'G-09SEML3SBN');
			};
		});
	</script>

	<!-- Seguridad (Evitar iframes no deseados) -->
	<script>
		if (window.top != window.self) {
			window.top.location.replace(window.self.location.href);
		}
	</script>
</head>

<body id="kt_body" class="app-blank">
	<script>
		var themeMode = "dark";
		if (document.documentElement) {
			document.documentElement.setAttribute("data-bs-theme", themeMode);
		}
	</script>
	<div class="d-flex flex-column flex-root" id="kt_app_root">
		<div class="d-flex flex-column flex-lg-row flex-column-fluid">
			<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
				<div class="d-flex justify-content-between flex-column w-100 mw-450px">
					<div class="d-flex flex-stack py-2">
						<div class="me-2">
							<a href="https://resergo.uy/" class="btn btn-icon bg-light rounded-circle">
								<i class="ki-duotone ki-black-left fs-2 text-gray-800"></i>
							</a>
						</div>
						<div class="m-0">
							<span class="text-gray-400 fw-bold fs-5 me-2" data-kt-translate="sign-up-head-desc">¿Ya formas parte?</span>
							<a href="https://admin.resergo.uy" target="_blank" class="link-info fw-bold fs-5"
								title="Inicia sesión en el panel de administración de ReserGo"
								aria-label="Inicia sesión en el panel de administración de ReserGo"
								data-kt-translate="sign-up-head-link">Inicia sesión</a>
						</div>
					</div>
					<div class="py-20">
						<form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" action="registro" method="POST">
							<div class="text-start mb-10">
								<h1 class="mb-3 fs-3x" data-kt-translate="sign-up-title">Crea tu cuenta</h1>
								<div class="text-gray-400 fw-semibold fs-6" data-kt-translate="general-desc">Ingresa tus datos para crear tu cuenta</div>
							</div>
							<div class="row fv-row mb-7">
								<div class="col-xl-6">
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="Juan" name="nombre" autocomplete="off" data-kt-translate="sign-up-input-first-name" required />
								</div>
								<div class="col-xl-6">
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="Pérez" name="apellido" autocomplete="off" data-kt-translate="sign-up-input-last-name" required />
								</div>
							</div>
							<div class="fv-row mb-10">
								<input class="form-control form-control-lg form-control-solid" type="text" placeholder="Barbería Los Amigos" name="nombre_barberia" autocomplete="off" required />
							</div>
							<div class="fv-row mb-10">
								<input class="form-control form-control-lg form-control-solid" type="email" placeholder="juan.perez@example.com" name="email" autocomplete="off" data-kt-translate="sign-up-input-email" required />
							</div>
							<div class="fv-row mb-10">
								<input class="form-control form-control-lg form-control-solid" type="tel" placeholder="0987654321" name="telefono" autocomplete="off" data-kt-translate="sign-up-input-telefono" required />
							</div>
							<div class="fv-row mb-10" data-kt-password-meter="true">
								<div class="mb-1">
									<div class="position-relative mb-3">
										<input class="form-control form-control-lg form-control-solid" type="password" placeholder="Contraseña123" name="password" autocomplete="off" data-kt-translate="sign-up-input-password" required />
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
											<i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
										</span>
									</div>
									<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
									</div>
								</div>
								<div class="text-muted" data-kt-translate="sign-up-hint">Usa al menos 8 caracteres con letras y números</div>
							</div>
							<div class="d-flex flex-stack">
								<button type="submit" class="btn btn-info me-10" id="kt_button_1">
									<span class="indicator-label">Crear cuenta</span>
									<span class="indicator-progress">
										Creando tu cuenta... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
									</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var hostUrl = "assets/";
	</script>
	<script src="assets/plugins/global/plugins.bundle.js"></script>
	<script src="assets/js/scripts.bundle.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const button = document.querySelector("#kt_button_1");
			const form = document.getElementById('kt_sign_up_form');

			form.addEventListener('submit', function(event) {
				event.preventDefault();

				// Validar el formulario antes de enviar
				if (!form.checkValidity()) {
					form.reportValidity();
					return;
				}

				button.setAttribute('data-kt-indicator', 'on');
				button.disabled = true;

				const data = Object.fromEntries(new FormData(form).entries());

				fetch('registro', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(data)
					})
					.then(response => response.json())
					.then(result => {
						if (result.success) {
							alert('Registro exitoso');
							window.location.href = 'https://admin.resergo.uy/';
						} else {
							alert('Error: ' + result.message);
							button.disabled = false; // Rehabilitar el botón en caso de error
						}
					})
					.catch(error => {
						console.error('Error al registrar:', error);
						alert('Hubo un problema al procesar el registro.');
						button.disabled = false; // Rehabilitar el botón en caso de error
					})
					.finally(() => {
						button.removeAttribute('data-kt-indicator');
					});
			});
		});
	</script>
</body>

</html>