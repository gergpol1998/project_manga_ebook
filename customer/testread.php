<?php
include "function.php";
connectdb();
echo "<script src='function.js'></script>";

if (isset($_GET['bookid'])) {
    $bookid = $_GET['bookid'];
    $sqlbook = select_where("book_name,book_test","book","book_id = '$bookid'");
        if($sqlbook->num_rows > 0){
            $row = $sqlbook->fetch_assoc();
        }
}
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9,
    'mirrorMargins' => true,

    'fontDir' => array_merge($fontDirs, [
        __DIR__ . 'vendor/mpdf/mpdf/custom/font/directory',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNew Italic.ttf',
            'B' => 'THSarabunNew Bold.ttf',
            'U' => 'THSarabunNew BoldItalic.ttf'
        ]
    ],
    'default_font' => 'thsarabun',
    'defaultPageNumStyle' => 1
]);

$mpdf->SetTitle($row['book_name']); // กำหนดชื่อไฟล์ PDF ตามชื่อเรื่อง

$mpdf->SetWatermarkText('MUTEBOOK');
$mpdf->showWatermarkText = true; // ต้องเปิดใช้ showWatermarkText ก่อน
$mpdf->watermarkTextAlpha = 0.5; // กำหนดค่า opacity ของ watermark

$pagecount = $mpdf->SetSourceFile($row['book_test']);


for ($i = 1; $i <= $pagecount; $i++) {
    $tplId = $mpdf->ImportPage($i);
    $mpdf->UseTemplate($tplId);
    $mpdf->SetDisplayMode('real');
    if ($i !== $pagecount){
        $mpdf->AddPage();
    }
}
$mpdf->Output();
connectdb()->close();
?>
