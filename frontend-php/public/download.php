<?php
// download.php - Professional Invoice Generator & Vulnerable Endpoint
$file = $_GET['file'] ?? '';
$appMode = getenv('APP_MODE') ?: 'vulnerable';

if (empty($file)) {
    die("Error: No se ha especificado ningun archivo.");
}

// Si es una factura, generamos una profesional en HTML (que el navegador trata como documento)
if (strpos($file, 'invoice_') === 0 || strpos($file, 'report_') === 0) {
    renderProfessionalInvoice($file);
    exit;
}

// Lógica de descarga normal (Vulnerable a Path Traversal)
if ($appMode === 'secure') {
    $allowed = ['invoice_1023.pdf', 'invoice_0988.pdf'];
    if (!in_array($file, $allowed)) {
        header("HTTP/1.1 403 Forbidden");
        die("Acceso denegado.");
    }
}

$path = "/var/www/html/assets/invoices/" . $file;
if (!file_exists($path)) { $path = $file; }

if (file_exists($path) && is_readable($path)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($path) . '"');
    readfile($path);
} else {
    echo "Error: El archivo no existe. Ruta: " . htmlspecialchars($path);
}

function renderProfessionalInvoice($filename) {
    $isInvoice = strpos($filename, 'invoice') !== false;
    $title = $isInvoice ? "FACTURA COMERCIAL" : "INFORME DE SEGURIDAD";
    $id = strtoupper(substr(md5($filename), 0, 8));
    $date = date('d/m/Y');
    
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Sofia Solutions - <?= $title ?></title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap');
            body { font-family: 'Inter', sans-serif; margin: 0; padding: 40px; color: #1e293b; background: #f8fafc; }
            .invoice-box { max-width: 800px; margin: auto; padding: 40px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
            .header { display: flex; justify-content: space-between; align-items: start; border-bottom: 2px solid #f1f5f9; padding-bottom: 30px; margin-bottom: 30px; }
            .logo { width: 180px; }
            .company-info { text-align: right; font-size: 0.9rem; color: #64748b; }
            .details { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
            .details h3 { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; margin-bottom: 8px; }
            .table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
            .table th { text-align: left; background: #f8fafc; padding: 12px; font-size: 0.85rem; color: #475569; }
            .table td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; }
            .total-section { text-align: right; }
            .total-row { display: flex; justify-content: flex-end; gap: 20px; margin-bottom: 8px; }
            .total-label { color: #64748b; }
            .total-amount { font-weight: 800; color: #8b5cf6; font-size: 1.4rem; }
            .footer { margin-top: 60px; padding-top: 20px; border-top: 1px solid #f1f5f9; font-size: 0.75rem; color: #94a3b8; text-align: center; }
            @media print { body { background: #fff; padding: 0; } .invoice-box { box-shadow: none; } }
        </style>
    </head>
    <body>
        <div class="invoice-box">
            <div class="header">
                <img src="/assets/sofia-logo-login.png" class="logo" alt="Sofia Solutions">
                <div class="company-info">
                    <strong>Sofia Solutions S.L.</strong><br>
                    Paseo de la Castellana 259, Madrid<br>
                    CIF: B-88776655<br>
                    soporte@sofia-solutions.com
                </div>
            </div>
            
            <div class="details">
                <div>
                    <h3>FACTURADO A:</h3>
                    <strong>Cliente Corporativo</strong><br>
                    Suscripción Activa Sofia Cloud<br>
                    ID Cliente: SF-<?= rand(1000, 9999) ?>
                </div>
                <div style="text-align: right;">
                    <h3>DETALLES:</h3>
                    Nº Documento: <strong>#<?= $id ?></strong><br>
                    Fecha: <strong><?= $date ?></strong><br>
                    Vencimiento: <strong>30 días</strong>
                </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>CONCEPTO</th>
                        <th>CANTIDAD</th>
                        <th style="text-align: right;">PRECIO</th>
                        <th style="text-align: right;">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($isInvoice): ?>
                    <tr>
                        <td><strong>Plan Business Max - Suscripción Mensual</strong><br><small>Monitorización SOC 24/7 y Protección Endpoints</small></td>
                        <td>1</td>
                        <td style="text-align: right;">1.239,67 €</td>
                        <td style="text-align: right;">1.239,67 €</td>
                    </tr>
                    <tr>
                        <td><strong>IR Retainer (Pack 10 horas)</strong><br><small>Respuesta ante incidentes priorizada</small></td>
                        <td>1</td>
                        <td style="text-align: right;">260,33 €</td>
                        <td style="text-align: right;">260,33 €</td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td><strong>Auditoría de Perímetro Externo</strong><br><small>Análisis de vulnerabilidades y exposición</small></td>
                        <td>1</td>
                        <td style="text-align: right;">0,00 €</td>
                        <td style="text-align: right;">INCLUIDO</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row"><span class="total-label">Subtotal:</span> <strong>1.500,00 €</strong></div>
                <div class="total-row"><span class="total-label">IVA (21%):</span> <strong>315,00 €</strong></div>
                <div class="total-row" style="margin-top: 10px; padding-top: 10px; border-top: 2px solid #f1f5f9;">
                    <span class="total-label" style="font-weight: 700; color: #1e293b;">TOTAL:</span>
                    <span class="total-amount">1.815,00 €</span>
                </div>
            </div>

            <div class="footer">
                Este documento es un comprobante oficial de Sofia Solutions S.L. emitido de forma electrónica.<br>
                Gracias por confiar en nuestra tecnología para proteger sus activos más críticos.
            </div>
            
            <div style="margin-top: 30px; text-align: center;" class="no-print">
                <button onclick="window.print()" style="padding: 10px 24px; border-radius: 8px; background: #8b5cf6; color: white; border: none; font-weight: 700; cursor: pointer;">Imprimir o Guardar como PDF</button>
            </div>
        </div>
        <style>@media print { .no-print { display: none; } }</style>
    </body>
    </html>
    <?php
}
