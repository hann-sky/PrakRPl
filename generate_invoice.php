<?php
require 'vendor/autoload.php'; // Pastikan dompdf terinstal dan autoload dari composer digunakan

use Dompdf\Dompdf;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $html = '<html><body>';
    $html .= '<h1 style="text-align: center;">Data Pemesanan Tiket</h1>';
    $html .= '<table style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr><td style="border: 1px solid #000; padding: 8px;">Nama</td><td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($data['nama']) . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid #000; padding: 8px;">Destinasi</td><td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($data['destinasi']) . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid #000; padding: 8px;">Jumlah Tiket</td><td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($data['jumlah_tiket']) . '</td></tr>';
    $html .= '<tr><td style="border: 1px solid #000; padding: 8px;">Tanggal Pemesanan</td><td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($data['tanggal_pemesanan']) . '</td></tr>';
    $html .= '</table>';
    $html .= '</body></html>';

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    header('Content-Type: application/pdf');
    echo $dompdf->output();
}
?>
