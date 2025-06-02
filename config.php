<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uas_pbw';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); 
    }
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: allert.php");
        exit;
    }
}

$site_name = "Sistem Informasi Akademik";
$site_year = "2025";
$current_date = date("Y/m/d");
?>
