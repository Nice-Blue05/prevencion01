<?php
// Copia este archivo como config.php y completa tus datos. config.php NO se sube a GitHub.
define('DB_HOST', 'sql112.byetcluster.com');
define('DB_NAME', 'b18_42651620_voz_segura');
define('DB_USER', 'b18_42651620');
define('DB_PASS', 'liana777.');

               // Brevo (https://app.brevo.com → SMTP y API → Claves API)
define('BREVO_API_KEY',      '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');
define('BREVO_SENDER_EMAIL', '75854085@iestpasm.edu.pe'); // debe estar verificado como remitente en Brevo
define('BREVO_SENDER_NAME',  'Voz Segura');
define('NOTIFY_EMAIL',       '75854085@iestpasm.edu.pe');               // recibe aviso de cada nuevo reporte
define('BREVO_VERIFY_SSL',   true);                // si falla cURL en XAMPP, ver README

define('ADMIN_PASSWORD', 'cambiame123');                          // contraseña del panel admin.php

session_start();

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $ex) {
            die('No se pudo conectar a la base de datos. Revisa config.php y que MySQL esté activo en XAMPP.');
        }
    }
    return $pdo;
}

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_ok(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

function enviar_brevo(?string $email, string $nombre, string $asunto, string $html): bool {
    if (!$email || BREVO_API_KEY === 'TU_API_KEY_DE_BREVO') return false;
    $payload = json_encode([
        'sender'      => ['name' => BREVO_SENDER_NAME, 'email' => BREVO_SENDER_EMAIL],
        'to'          => [['email' => $email, 'name' => $nombre ?: $email]],
        'subject'     => $asunto,
        'htmlContent' => $html,
    ]);
    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => BREVO_VERIFY_SSL,
        CURLOPT_HTTPHEADER     => ['accept: application/json', 'api-key: '.BREVO_API_KEY, 'content-type: application/json'],
        CURLOPT_POSTFIELDS     => $payload,
    ]);
    curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $http >= 200 && $http < 300;
}
