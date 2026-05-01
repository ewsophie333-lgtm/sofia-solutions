<?php
// download.php - Vulnerable Endpoint
$file = $_GET['file'] ?? '';
$appMode = getenv('APP_MODE') ?: 'vulnerable';

if (empty($file)) {
    die("Error: No se ha especificado ningun archivo.");
}

// Bloqueo en Modo Seguro
if ($appMode === 'secure') {
    $allowed = ['invoice_1023.pdf', 'invoice_0988.pdf'];
    if (!in_array($file, $allowed)) {
        header("HTTP/1.1 403 Forbidden");
        die("Acceso denegado.");
    }
}

// En una app real, los archivos estarian aqui
$path = "/var/www/html/assets/invoices/" . $file;

// Si el archivo no existe en la carpeta, pero el usuario usa ../..
// PHP intentara leerlo si el servidor tiene permisos
if (!file_exists($path)) {
    // Simulacion: si el archivo empieza por .. es un ataque de traversal
    // En modo vulnerable, lo dejamos pasar
    $path = $file; 
}

if (file_exists($path) && is_readable($path)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    readfile($path);
} else {
    echo "Error: El archivo no existe o no se puede leer. Ruta intentada: " . htmlspecialchars($path);
}
