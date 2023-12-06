<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$defaultSettings = '{
                    "file-manager-initial-path":"/",
                    "terminal-execution-mode":"system",
                    "theme-os":"theme-dark",
                    "file-manager-view-mode":"grid"
                    }';
if (!isset($_SESSION['sessionSettings'])) {
    $sessionSettings = $defaultSettings;
} else {
    $sessionSettings = $_SESSION['sessionSettings'];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch (true) {
        case isset($_POST['getSettings']):
            if (isset($_SESSION['sessionSettings'])) {
                echo json_encode($_SESSION['sessionSettings']);
            } else {
                echo $defaultSettings;
            }
            exit;
        case isset($_POST['saveSettings']):
            $settingsData = $_POST['saveSettings'];
            $sessionData = json_decode($settingsData);
            if (!empty($settingsData)) {
                $_SESSION['sessionSettings'] = $sessionData;
            }
            exit;
        case isset($_POST['resetSettings']):
            $sessionSettings = $defaultSettings;
            session_destroy();
            exit;
    }
}
