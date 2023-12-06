<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_start();
    phpinfo();
    $info = ob_get_contents();
    ob_end_clean();
    echo "<pre>$info</pre>";
    exit;
}
