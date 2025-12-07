<?php
require_once '../includes/config.php'; // Ensure session is started
session_destroy();
header('Location: index.php');
exit;
?>
