<?php
require_once __DIR__ . '/vendor/autoload.php';
use Endroid\QrCode\QrCode;
$qrCode = new QrCode($_GET['uri']);
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
?>
