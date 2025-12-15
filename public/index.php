<?php
// Entry point aplikasi
require_once '../app/config/config.php';
require_once '../app/config/database.php';
require_once '../core/App.php';
require_once '../core/Controller.php';
require_once '../core/Database.php';

// Load helpers
require_once '../app/helpers/Flasher.php';
require_once '../app/helpers/TopsisHelper.php';

$app = new App();
?>
