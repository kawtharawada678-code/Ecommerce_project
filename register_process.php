<?php
require "config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST['full_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password_raw = $_POST['password'] ?? null;

    // safety check
    if (!$full_name || !$email || !$password_raw) {
        die("All fields are required!");
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (full_name, email, password)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $full_name,
        $email,
        $password
    ]);

    header("Location: login.php");
    exit;
}
?>