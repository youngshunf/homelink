<?php
error_reporting(E_ERROR)WxpayAPI 'phpqrcode/phpqrcode.php';
$url = urldecode($_GET["data"]);
QRcode::png($url);
