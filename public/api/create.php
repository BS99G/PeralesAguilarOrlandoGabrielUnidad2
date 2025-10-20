<?php
header('Content-Type: application/json');

// 1. Conectar a la base de datos
$dbPath = __DIR__ . '/../../db/database.sqlite';
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la tabla si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS links (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        short_code TEXT NOT NULL UNIQUE,
        original_url TEXT NOT NULL,
        clicks INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
    exit;
}

// 2. Obtener la URL larga
$data = json_decode(file_get_contents('php://input'), true);
$originalUrl = filter_var($data['url'] ?? '', FILTER_SANITIZE_URL);

if (empty($originalUrl) || !filter_var($originalUrl, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'URL inválida o vacía.']);
    exit;
}

// 3. Generar código corto
function generateShortCode($length = 6) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $result;
}

$shortCode = generateShortCode();
// (En un proyecto real, deberías verificar que el shortCode no exista ya en la BD)

// 4. Guardar en la BD
try {
    $stmt = $pdo->prepare("INSERT INTO links (short_code, original_url) VALUES (?, ?)");
    $stmt->execute([$shortCode, $originalUrl]);

    $shortUrl = "https://{$_SERVER['HTTP_HOST']}/{$shortCode}";

    echo json_encode([
        'short_url' => $shortUrl,
        'original_url' => $originalUrl
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al guardar el enlace: ' . $e->getMessage()]);
}