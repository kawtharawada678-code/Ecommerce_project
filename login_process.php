<?php
session_start();
require "config/database.php";

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {

    // IMPORTANT: keep ONE session structure for whole project
    $_SESSION['user'] = [
        'id'        => $user['id'],
        'full_name' => $user['full_name'],
        'email'     => $user['email'],
        'role'      => $user['role'] ?? 'user'
    ];

    header("Location: index.php");
    exit;

} else {
    echo "Wrong email or password";
}