<?php
// --- INICIO BLOQUE DE REDIRECCIÓN ---
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $shortCode = htmlspecialchars($_GET['code']);
    $dbPath = __DIR__ . '/../db/database.sqlite';

    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT original_url FROM links WHERE short_code = ?");
        $stmt->execute([$shortCode]);
        $link = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($link) {
            // Incrementar contador de clics (opcional)
            $stmt_click = $pdo->prepare("UPDATE links SET clicks = clicks + 1 WHERE short_code = ?");
            $stmt_click->execute([$shortCode]);

            // Redirección permanente
            header('Location: ' . $link['original_url'], true, 301);
            exit;
        } else {
            // Opcional: manejar código no encontrado
            // Por ahora, simplemente cargará la página principal
        }
    } catch (PDOException $e) {
        // No hacer nada, solo cargar la página principal
    }
}
// --- FIN BLOQUE DE REDIRECCIÓN ---
?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkShrink - Acortador Futurista</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="h-full gradient-bg text-white font-mono">
    <main class="min-h-full flex flex-col items-center justify-center p-6">
        <!-- Header -->
        <header class="text-center mb-12 float-animation">
            <h1 class="text-6xl font-bold mb-4 neon-text">LinkShrink</h1>
            <p class="text-xl text-blue-300">Acortador de enlaces del futuro</p>
            <div class="mt-4 flex justify-center space-x-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                <div class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
            </div>
        </header>

        <!-- Main Container -->
        <div class="glass-effect rounded-2xl p-8 w-full max-w-2xl pulse-border">
            <!-- URL Input Form -->
            <form id="urlForm" class="mb-8">
                <label for="urlInput" class="block text-sm font-medium text-blue-300 mb-3">
                    Ingresa tu enlace largo
                </label>
                <div class="flex flex-col sm:flex-row gap-4">
                    <input 
                        type="url" 
                        id="urlInput"
                        placeholder="https://ejemplo.com/enlace-muy-largo..."
                        class="flex-1 px-4 py-3 bg-gray-900/50 border border-blue-500/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-300"
                        required
                    >
                    <button 
                        type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 glow-effect"
                    >
                        Acortar
                    </button>
                </div>
            </form>

            <!-- Result Section -->
            <div id="resultSection" class="hidden">
                <div class="glass-effect rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-green-400 mb-3">¡Enlace acortado exitosamente!</h3>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="text" 
                            id="shortUrl"
                            readonly
                            class="flex-1 px-4 py-2 bg-gray-800 border border-green-500/30 rounded-lg text-green-300 focus:outline-none"
                        >
                        <button 
                            id="copyBtn"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition-all duration-300"
                        >
                            Copiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="glass-effect rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-400" id="totalLinks">0</div>
                    <div class="text-sm text-gray-400">Enlaces creados</div>
                </div>
                <div class="glass-effect rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-purple-400" id="totalClicks">0</div>
                    <div class="text-sm text-gray-400">Clics totales</div>
                </div>
                <div class="glass-effect rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-cyan-400">99.9%</div>
                    <div class="text-sm text-gray-400">Uptime</div>
                </div>
            </div>

            <!-- Recent Links -->
            <div id="recentLinks" class="hidden">
                <h3 class="text-lg font-semibold text-blue-300 mb-4">Enlaces recientes</h3>
                <div id="linksList" class="space-y-3 max-h-60 overflow-y-auto">
                    <!-- Links will be added here -->
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 text-center text-gray-400">
            <p class="text-sm">Tecnología cuántica de acortamiento • Seguro y confiable</p>
        </footer>
    </main>

    <script src="assets/app.js" defer></script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9901a0ba2103d875',t:'MTc2MDcyMzMxNy4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
