<?php
require '../session.php';
unset($_SESSION['data']);
session_destroy();
header('location: index.php');
?>