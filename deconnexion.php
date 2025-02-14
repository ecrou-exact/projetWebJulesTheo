<?php
session_start();
include 'user.php';
unset($_SESSION['utilisateur']);

session_destroy();
header("Location: index.php");
exit();
