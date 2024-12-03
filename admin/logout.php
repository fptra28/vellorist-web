<?php
session_start();

//Unset Semua Session Variabel
unset($_SESSION['username']);
unset($_SESSION['id_users']);

//Unset All
session_unset();

//Destroy Session
session_destroy();

// Arahkan ke halaman login
header("location: ../login.php?pesan=logout");
exit;
?>