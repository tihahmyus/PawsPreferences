<?php
session_start();
session_unset();
session_destroy();
header("Location: caregiver-login.php");
exit;
?>
