<?php
// index.php - Carga de planes y configuración de sesión

// Cargar el autoload de Composer para utilizar las clases de la aplicación
require_once 'vendor/autoload.php';

use App\Plan;

// Iniciar la sesión para gestionar CSRF y datos de usuario
session_start();

// Generar y almacenar un token CSRF si no existe en la sesión
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token seguro para proteger formularios
}

// Obtener el token CSRF de la sesión
$csrf_token = $_SESSION['csrf_token'];

// Crear una instancia de la clase Plan y obtener todos los planes disponibles
$planObj = new Plan();
$planes = $planObj->getAllPlans();

// echo json_encode($planes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta Básicos -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReserGo Uruguay - Software de Reserva y Gestión de Barberías Online</title>
    <meta name="description" content="ReserGo es el software líder en gestión de barberías en Uruguay. Simplifica reservas, administra clientes y optimiza tu barbería.">
    <meta name="keywords" content="ReserGo, barberías, software de gestión, reservas online, administración de clientes, Uruguay">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Spanish">
    <meta name="author" content="ReserGo">
    <link rel="canonical" href="https://resergo.uy/">

    <!-- SEO Local y Geolocalización -->
    <meta name="geo.region" content="UY">
    <meta name="geo.placename" content="Uruguay">
    <meta name="geo.position" content="-34.9011127;-56.1645314">
    <meta name="ICBM" content="-34.9011127, -56.1645314">
    <link rel="alternate" hreflang="es-UY" href="https://resergo.uy/">
    <link rel="alternate" hreflang="es" href="https://resergo.uy/">

    <!-- Open Graph para Redes Sociales -->
    <meta property="og:title" content="ReserGo - Software de Gestión para Barberías">
    <meta property="og:site_name" content="ReserGo">
    <meta property="og:url" content="https://resergo.uy/">
    <meta property="og:description" content="Descubre cómo ReserGo facilita la administración de barberías con su potente software de gestión de reservas y clientes.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://resergo.uy/assets/media/resergo/social-banner.jpg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="ReserGo - Software de Gestión para Barberías">
    <meta name="twitter:description" content="ReserGo es la solución integral para la gestión de barberías en Uruguay. Reserva online, administración de clientes y mucho más.">
    <meta name="twitter:image" content="https://resergo.uy/assets/media/resergo/social-banner.jpg">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/resergo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/media/resergo/32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/media/resergo/16x16.png">
    <link rel="manifest" href="./manifest.json">
    <meta name="msapplication-TileColor" content="#8936FF">
    <meta name="theme-color" content="#090524">

    <!-- Mobile & UX Optimization -->
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="320">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">

    <!-- JSON-LD Schema Markup para SEO (SoftwareApplication) -->
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "SoftwareApplication",
            "name": "ReserGo",
            "operatingSystem": "Web",
            "applicationCategory": "BusinessApplication",
            "offers": {
                "@type": "Offer",
                "price": "800",
                "priceCurrency": "UYU"
            },
            "creator": {
                "@type": "Organization",
                "name": "ReserGo"
            },
            "description": "Software de gestión para barberías en Uruguay: reserva online, administración de clientes y optimización del negocio.",
            "url": "https://resergo.uy/",
            "image": "https://resergo.uy/assets/media/resergo/social-banner.jpg",
            "sameAs": [
                "https://www.instagram.com/resergo.uy/",
                "https://wa.me/59894306272"
            ]
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

    <!-- CSS -->
    <link href="./output.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NLDTZ79Q"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <header class="fixed w-full z-20">
        <nav class="bg-white border-gray-200 py-2.5 dark:bg-gray-900">
            <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
                <a href="#" class="flex items-center">
                    <img src="./assets/media/resergo/logo1.svg" width="64" height="64" class="h-16 mr-3 sm:h-12" alt="Logo de ReserGo" />
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">ReserGo</span>
                </a>
                <div class="flex items-center lg:order-2">
                    <!-- Opcional: Botón o enlace para redirigir a la página de prueba -->
                    <a href="registro"
                        class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 sm:mr-2 lg:mr-0 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                        <i class="fas fa-rocket mr-2"></i>Prueba Gratis
                    </a>
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Abrir menú principal</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="#inicio" title="Inicio" aria-label="Inicio"
                                class="block py-2 pl-3 pr-4 text-white bg-purple-700 rounded lg:bg-transparent lg:text-purple-700 lg:p-0 dark:text-white"
                                aria-current="page">Inicio</a>
                        </li>
                        <li>
                            <a href="#servicios" title="Servicios" aria-label="Servicios"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">
                                Servicios</a>
                        </li>
                        <li>
                            <a href="#planes" title="Planes" aria-label="Planes"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">
                                Planes</a>
                        </li>
                        <li>
                            <a href="#faq" title="FAQ" aria-label="FAQ"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">
                                FAQ</a>
                        </li>
                        <li>
                            <a href="#contacto" title="Contacto" aria-label="Contacto"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">
                                Contacto</a>
                        </li>
                        <li>
                            <a href="https://admin.resergo.uy/login" title="Accede a una prueba gratuita de ReserGo" aria-label="Accede a una prueba gratuita de ReserGo"
                                class="block py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-purple-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">
                                Accede a una prueba gratuita
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Sección #inicio sin fondo adicional, solo con el degradado de fondo distante -->
    <section class="relative bg-white dark:bg-gray-900" id="inicio">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1 class="max-w-2xl mb-4 text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
                    Simplifica la gestión <br> de tu barbería con ReserGo
                </h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                    ReserGo te ofrece una plataforma integral para manejar las reservas de tus clientes, gestionar
                    múltiples sucursales y optimizar tus servicios. Facilita la administración diaria con un
                    sistema intuitivo y enfocado en maximizar la eficiencia.
                </p>
                <div class="space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                    <a href="#servicios" class="inline-flex items-center justify-center w-full px-5 py-3 text-sm font-medium text-center text-gray-900 border border-gray-200 rounded-lg sm:w-auto hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        <i class="fas fa-search w-4 h-4 mr-2 text-gray-500 dark:text-gray-400"></i>
                        Conoce nuestros servicios
                    </a>
                    <a href="registro" class="inline-flex items-center justify-center w-full px-5 py-3 mb-2 mr-2 text-sm font-medium text-white bg-purple-600 border border-gray-200 rounded-lg sm:w-auto focus:outline-none hover:bg-purple-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-purple-800 dark:text-white dark:border-gray-600 dark:hover:bg-purple-700">
                        <i class="fas fa-user-plus w-4 h-4 mr-2 text-white dark:text-white"></i>
                        Comienza gratis ahora mismo
                    </a>
                </div>
            </div>
            <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                <img src="./assets/media/resergo/hero.webp" alt="ReserGo - Gestión de Barberías">
            </div>
        </div>
    </section>
    <section class="bg-gray-50 dark:bg-gray-800" id="servicios">
        <div class="max-w-screen-xl px-4 py-8 mx-auto space-y-12 lg:space-y-20 lg:py-24 lg:px-6">
            <!-- Row -->
            <div class="items-center gap-8 lg:grid lg:grid-cols-2 xl:gap-16">
                <div class="text-gray-500 sm:text-lg dark:text-gray-400">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Gestiona tu barbería de forma simple y eficiente
                    </h2>
                    <p class="mb-8 font-light lg:text-xl">
                        ReserGo te brinda todas las herramientas que necesitas para optimizar la administración de tu
                        barbería. Desde la gestión de reservas hasta el control de múltiples sucursales, todo en una
                        sola plataforma intuitiva.
                    </p>
                    <!-- List -->
                    <ul role="list" class="pt-8 space-y-5 border-t border-gray-200 my-7 dark:border-gray-700">
                        <li class="flex space-x-3">
                            <!-- Icon -->
                            <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">
                                Gestión centralizada de múltiples sucursales
                            </span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Icon -->
                            <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">
                                Calendario moderno para agendar citas fácilmente
                            </span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Icon -->
                            <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">
                                Análisis detallado de ingresos y servicios más solicitados
                            </span>
                        </li>
                    </ul>
                    <p class="mb-8 font-light lg:text-xl">
                        Simplifica la gestión diaria y dedica más tiempo a lo que realmente importa: ¡tus clientes!
                    </p>
                </div>
                <img class="hidden w-full mb-4 rounded-lg lg:mb-0 lg:flex" src="./assets/media/resergo/feature-1.webp"
                    alt="Funcionalidades de gestión de ReserGo">
            </div>
            <!-- Row -->
            <div class="items-center gap-8 lg:grid lg:grid-cols-2 xl:gap-16">
                <img class="hidden w-full mb-4 rounded-lg lg:mb-0 lg:flex" src="./assets/media/resergo/feature-2.webp"
                    alt="Características avanzadas de ReserGo">
                <div class="text-gray-500 sm:text-lg dark:text-gray-400">
                    <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        Todo lo que necesitas para crecer
                    </h2>
                    <p class="mb-8 font-light lg:text-xl">
                        ReserGo es la solución ideal para llevar tu barbería al siguiente nivel, con herramientas
                        avanzadas que facilitan la administración y la comunicación con tus clientes.
                    </p>
                    <!-- List -->
                    <ul role="list" class="pt-8 space-y-5 border-t border-gray-200 my-7 dark:border-gray-700">
                        <li class="flex space-x-3">
                            <!-- Icon -->
                            <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">
                                Reportes dinámicos para entender mejor a tus clientes
                            </span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Icon -->
                            <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">
                                Personalización de horarios y gestión de barberos
                            </span>
                        </li>
                        <li class="flex space-x-3">
                            <!-- Icon -->
                            <i class="fas fa-check-circle flex-shrink-0 w-5 h-5 text-purple-500 dark:text-purple-400"></i>
                            <span class="text-base font-medium leading-tight text-gray-900 dark:text-white">
                                Notificaciones automáticas y recordatorios
                            </span>
                        </li>
                    </ul>
                    <p class="font-light lg:text-xl">
                        Con ReserGo, tu barbería estará siempre un paso adelante.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-white dark:bg-gray-900" id="confianza-resergo">
        <div class="items-center max-w-screen-xl px-4 py-8 mx-auto lg:grid lg:grid-cols-4 lg:gap-16 xl:gap-24 lg:py-24 lg:px-6">
            <div class="col-span-2 mb-8">
                <p class="text-lg font-medium text-purple-600 dark:text-purple-500">Confianza en ReserGo</p>
                <h2 class="mt-3 mb-4 text-3xl font-extrabold tracking-tight text-gray-900 md:text-3xl dark:text-white">
                    Innovando para liderar la gestión de reservas en la región
                </h2>
                <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">
                    En ReserGo, nos enfocamos en crear un sistema eficiente y seguro para la gestión de reservas. Nuestro objetivo es ayudar a los negocios a optimizar sus operaciones y mejorar la experiencia de sus clientes.
                </p>
            </div>
            <div class="col-span-2 space-y-8 md:grid md:grid-cols-2 md:gap-12 md:space-y-0">
                <div>
                    <i class="fas fa-server fa-2x w-10 h-10 mb-2 text-purple-600 md:w-12 md:h-12 dark:text-purple-500"></i>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Disponibilidad garantizada</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Nuestro objetivo es mantener una plataforma con alta disponibilidad para las reservas.</p>
                </div>
                <div>
                    <i class="fas fa-users fa-2x w-10 h-10 mb-2 text-purple-600 md:w-12 md:h-12 dark:text-purple-500"></i>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Conectando con futuros clientes</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Trabajamos para ofrecer una gestión eficiente de reservas para negocios en crecimiento.</p>
                </div>
                <div>
                    <i class="fas fa-globe fa-2x w-10 h-10 mb-2 text-purple-600 md:w-12 md:h-12 dark:text-purple-500"></i>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Expansión futura</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Planeamos expandir nuestra presencia en más países, llevando innovación a la región.</p>
                </div>
                <div>
                    <i class="fas fa-cogs fa-2x w-10 h-10 mb-2 text-purple-600 md:w-12 md:h-12 dark:text-purple-500"></i>
                    <h3 class="mb-2 text-2xl font-bold dark:text-white">Optimización continua</h3>
                    <p class="font-light text-gray-500 dark:text-gray-400">Mejorando constantemente nuestro sistema para facilitar las reservas.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-white dark:bg-gray-900" id="planes">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:py-24 lg:px-6">
            <div class="max-w-screen-md mx-auto mb-8 text-center lg:mb-12">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Diseñado para barberías de todos los tamaños
                </h2>
                <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">
                    En ReserGo, ofrecemos soluciones flexibles para facilitar la gestión y el crecimiento de tu barbería. Elige el plan que mejor se adapte a tus necesidades.
                </p>
            </div>
            <div class="space-y-8 lg:grid lg:grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">
                <?php foreach ($planes as $plan): ?>
                    <article itemscope itemtype="http://schema.org/Product" class="flex flex-col max-w-lg p-6 mx-auto text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <header>
                            <h3 itemprop="name" class="mb-2 text-2xl font-semibold">
                                <?= htmlspecialchars($plan['nombre']); ?>
                            </h3>
                            <?php if ($plan['is_popular']): ?>
                                <span class="text-lg font-extrabold text-purple-600 bg-purple-100 rounded-full px-3 py-1 uppercase dark:text-purple-500 dark:bg-purple-200 mb-2 block">Más popular</span>
                            <?php endif; ?>
                            <p itemprop="description" class="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                                <?= htmlspecialchars($plan['descripcion']); ?>
                            </p>
                        </header>
                        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="flex items-baseline justify-center my-8">
                            <span itemprop="priceCurrency" content="UYU" class="mr-2 text-3xl font-extrabold">$</span>
                            <span itemprop="price" content="<?= htmlspecialchars($plan['precio_uyu']); ?>" class="mr-2 text-5xl font-extrabold"><?= htmlspecialchars(floor($plan['precio_uyu'])); ?></span>
                            <link itemprop="availability" href="http://schema.org/InStock">
                            <span class="text-gray-500 dark:text-gray-400">/mes</span>
                        </div>
                        <ul class="mb-8 space-y-4 text-left">
                            <?php foreach ($plan['features'] as $feature): ?>
                                <li class="flex items-center space-x-3">
                                    <i class="fas fa-check text-green-500 dark:text-green-400"></i>
                                    <span itemprop="feature"><?= htmlspecialchars($feature); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="flex justify-center space-x-2">
                            <button onclick="togglePaymentOptions('<?= $plan['id']; ?>')" class="flex-1 text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-200 font-medium rounded-lg text-sm px-4 py-2 text-center dark:text-white dark:focus:ring-purple-900 whitespace-nowrap">
                                <i class="fas fa-credit-card mr-2"></i>Pagar ahora
                            </button>
                        </div>
                        <div id="payment-options-<?= $plan['id']; ?>" class="hidden flex justify-center space-x-2 mt-2">
                            <a href="procesar_pago.php?plan_id=<?= $plan['id']; ?>"
                                class="flex-1 text-white font-medium rounded-lg text-sm px-4 py-2 text-center whitespace-nowrap"
                                style="background-color: #009EE3;">
                                <i class="fas fa-money-check-alt mr-2"></i>Mercado Pago
                            </a>
                            <a href="mailto:hola@resergo.uy?subject=Solicitud%20de%20pago%20por%20transferencia%20bancaria&body=Hola%2C%20quiero%20el%20<?= urlencode($plan['nombre']); ?>%20y%20pagar%20con%20transferencia%20bancaria.%0A%0APlan%3A%20<?= urlencode($plan['nombre']); ?>%0APrecio%3A%20<?= urlencode($plan['precio_uyu']); ?>%20UYU%0A%0AGracias."
                                class="flex-1 text-white font-medium rounded-lg text-sm px-4 py-2 text-center whitespace-nowrap"
                                style="background-color: #28a745;">
                                <i class="fas fa-university mr-2"></i>Transferencia bancaria
                            </a>
                        </div>
                        <script>
                            function togglePaymentOptions(planId) {
                                const options = document.getElementById('payment-options-' + planId);
                                options.classList.toggle('hidden');
                            }
                        </script>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section class="bg-white dark:bg-gray-900" id="faq">
        <div class="max-w-screen-xl px-4 pb-8 mx-auto lg:pb-24 lg:px-6">
            <h2
                class="mb-6 text-3xl font-extrabold tracking-tight text-center text-gray-900 lg:mb-8 lg:text-3xl dark:text-white">
                Preguntas Frecuentes
            </h2>
            <div class="max-w-screen-md mx-auto">
                <div id="accordion-flush" data-accordion="collapse"
                    data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                    data-inactive-classes="text-gray-500 dark:text-gray-400">
                    <!-- Pregunta 1 -->
                    <h3 id="accordion-flush-heading-1">
                        <button type="button"
                            class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-900 bg-white border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            data-accordion-target="#accordion-flush-body-1" aria-expanded="true"
                            aria-controls="accordion-flush-body-1">
                            <span>¿Cómo puedo gestionar varias sucursales con ReserGo?</span>
                            <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-1" class="" aria-labelledby="accordion-flush-heading-1">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">
                                Con ReserGo, puedes gestionar varias sucursales desde un solo panel de administración.
                                Simplemente agrega las sucursales y personaliza la configuración específica de cada una,
                                como horarios de atención, servicios ofrecidos y personal disponible.
                            </p>
                        </div>
                    </div>
                    <!-- Pregunta 2 -->
                    <h3 id="accordion-flush-heading-2">
                        <button type="button"
                            class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                            data-accordion-target="#accordion-flush-body-2" aria-expanded="false"
                            aria-controls="accordion-flush-body-2">
                            <span>¿Puedo enviar recordatorios automáticos a mis clientes?</span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">
                                Sí, ReserGo permite enviar recordatorios automáticos a tus clientes antes de sus citas.
                                Esto reduce las ausencias y mejora la organización de tu barbería.
                            </p>
                        </div>
                    </div>
                    <!-- Pregunta 3 -->
                    <h3 id="accordion-flush-heading-3">
                        <button type="button"
                            class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                            data-accordion-target="#accordion-flush-body-3" aria-expanded="false"
                            aria-controls="accordion-flush-body-3">
                            <span>¿Qué tipos de reportes ofrece ReserGo?</span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">
                                ReserGo ofrece una variedad de reportes, incluyendo ingresos mensuales, servicios más
                                solicitados, y análisis de clientes atendidos. Además, puedes personalizar los reportes
                                según las necesidades de tu barbería.
                            </p>
                        </div>
                    </div>
                    <!-- Pregunta 4 -->
                    <h3 id="accordion-flush-heading-4">
                        <button type="button"
                            class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
                            data-accordion-target="#accordion-flush-body-4" aria-expanded="false"
                            aria-controls="accordion-flush-body-4">
                            <span>¿Cómo puedo personalizar los horarios de atención?</span>
                            <svg data-accordion-icon="" class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h3>
                    <div id="accordion-flush-body-4" class="hidden" aria-labelledby="accordion-flush-heading-4">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">
                                Desde el panel de administración de ReserGo, puedes ajustar los horarios de atención de
                                cada sucursal según tus necesidades. Es fácil de configurar y puedes realizar cambios en
                                cualquier momento.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-gray-50 dark:bg-gray-800" id="prueba-gratuita">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:py-16 lg:px-6">
            <div class="max-w-screen-sm mx-auto text-center">
                <h2 class="mb-4 text-3xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">
                    Empieza tu prueba gratuita hoy mismo
                </h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">
                    Prueba ReserGo sin compromiso y descubre cómo puedes mejorar la gestión de tu barbería. Explora todas nuestras funcionalidades: gestión de citas, análisis de clientes, estadísticas y mucho más.
                </p>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">
                    Si prefieres explorar el sistema como visitante, sin necesidad de crear una cuenta, puedes hacerlo <a href="https://admin.resergo.uy" class="text-purple-600 hover:text-purple-800">explorando el panel de administración</a> y hacer clic en el botón <strong>Usar credenciales de prueba</strong> para acceder.
                </p>
                <a href="registro"
                    class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                    <i class="fas fa-rocket mr-2"></i>Comenzar prueba gratuita
                </a>
            </div>
        </div>
    </section>
    <section class="bg-white dark:bg-gray-900" id="contacto">
        <div class="isolate bg-white px-6 py-24 sm:py-10 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-balance text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">Contáctanos
                </h2>
                <p class="mt-2 text-lg leading-8 text-gray-600">¿Tienes alguna pregunta o comentario? ¡Estamos aquí para
                    ayudarte! Completa el formulario a continuación y nos pondremos en contacto contigo lo antes
                    posible.</p>
            </div>
            <form id="contact-form" action="send_email" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="first-name" class="block text-sm font-semibold leading-6 text-gray-900">Nombre</label>
                        <div class="mt-2.5">
                            <input type="text" name="from_name" id="first-name" autocomplete="given-name"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">Apellido</label>
                        <div class="mt-2.5">
                            <input type="text" name="user_lastname" id="last-name" autocomplete="family-name"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="company" class="block text-sm font-semibold leading-6 text-gray-900">Empresa</label>
                        <div class="mt-2.5">
                            <input type="text" name="user_company" id="company" autocomplete="organization"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-semibold leading-6 text-gray-900">Correo electrónico</label>
                        <div class="mt-2.5">
                            <input type="email" name="user_email" id="email" autocomplete="email"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="phone-number" class="block text-sm font-semibold leading-6 text-gray-900">Número de teléfono</label>
                        <div class="relative mt-2.5">
                            <div class="absolute inset-y-0 left-0 flex items-center">
                                <label for="country" class="sr-only">País</label>
                                <select id="country" name="user_country"
                                    class="h-full rounded-md border-0 bg-transparent bg-none py-0 pl-4 pr-9 text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                                    required>
                                    <option>UY</option>
                                    <option>AR</option>
                                    <option>US</option>
                                    <option>CA</option>
                                    <option>GB</option>
                                    <option>FR</option>
                                    <option>DE</option>
                                    <option>JP</option>
                                    <option>CN</option>
                                    <option>IN</option>
                                </select>
                            </div>
                            <input type="tel" name="user_phone" id="phone-number" autocomplete="tel"
                                class="block w-full rounded-md border-0 px-3.5 py-2 pl-20 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                pattern="[0-9]*" maxlength="10" required>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm font-semibold leading-6 text-gray-900">Mensaje</label>
                        <div class="mt-2.5">
                            <textarea name="message" id="message" rows="4"
                                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                required></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit" id="submit-button"
                        class="block w-full rounded-md bg-purple-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-purple-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar mensaje
                    </button>
                </div>
            </form>
            <div id="message-response" class="mt-5 text-center"></div>
        </div>
    </section>
    <footer class="bg-white dark:bg-gray-800">
        <div class="max-w-screen-xl p-4 py-6 mx-auto lg:py-16 md:p-8 lg:p-10">
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8">
            <div class="text-center">
                <a href="#"
                    class="flex items-center justify-center mb-5 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img src="./assets/media/resergo/logo1.svg" class="h-12 mr-3 sm:h-15" alt="Logo de ReserGo" /> ReserGo
                </a>
                <span class="block text-sm text-center text-gray-500 dark:text-gray-400">
                    © 2024 ReserGo™. Todos los derechos reservados.
                </span>
                <ul class="flex justify-center mt-5 space-x-5">
                    <!-- Instagram Link -->
                    <li>
                        <a href="https://www.instagram.com/resergo.uy/" target="_blank"
                            class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400"
                            aria-label="Instagram de ReserGo">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                    </li>
                    <!-- WhatsApp Link -->
                    <li>
                        <a href="https://wa.me/59894306272?text=¡Hola!%20Quisiera%20obtener%20más%20información%20sobre%20sus%20servicios"
                            target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400"
                            aria-label="Contactar por WhatsApp">
                            <i class="fab fa-whatsapp fa-lg"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
    <script>
        // Escuchar el evento de envío del formulario
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío predeterminado del formulario

            // Deshabilitar el botón de envío
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';

            // Crear un objeto FormData a partir del formulario
            const formData = new FormData(this);

            // Asegurarse de que el token CSRF se incluya en el envío
            const csrfToken = document.querySelector('input[name="csrf_token"]').value;
            formData.append('csrf_token', csrfToken);

            // Enviar la solicitud usando fetch
            fetch('send_email', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Mostrar mensaje de éxito o error en función de la respuesta
                    if (data.includes('El mensaje ha sido enviado correctamente')) {
                        alert('El mensaje ha sido enviado correctamente');
                    } else {
                        alert('Error al enviar el mensaje: ' + data);
                    }
                    // Recargar la página después de mostrar el mensaje
                    location.reload();
                })
                .catch(error => {
                    // Mostrar cualquier error que ocurra durante la solicitud
                    alert('Ocurrió un error: ' + error.message);
                    // Recargar la página después de mostrar el mensaje
                    location.reload();
                });
        });
    </script>
</body>

</html>