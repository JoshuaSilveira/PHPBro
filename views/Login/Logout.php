<?php session_start();

session_destroy();
header("Location: Login.php?logout=true");
exit();
?>