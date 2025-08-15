<?php
//header("Content-Type: image/png");
require('phpqrcode/qrlib.php');


if (isset($_GET['data']) && !empty($_GET['data'])):

QRcode::svg(base64_decode(htmlspecialchars($_GET['data'])), FALSE, 'H',5,4,FALSE,0xf2f2f2);

endif;
