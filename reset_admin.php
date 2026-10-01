<?php
require_once "config.php";

$email = "admin@scholartrack.com";
$password = "password";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

/* Check if admin exists */
$check = $pdo->prepare(
    "SELECT id FROM users WHERE email = ? LIMIT 1"
);

$check->execute([$email]);
$admin = $check->fetch();

if ($admin) {
    $update = $pdo->prepare(
        "UPDATE users
         SET fullname = ?, password = ?, role = 'admin'
         WHERE email = ?"
    );

    $update->execute([
        "ScholarTrack Administrator",
        $hashed_password,
        $email
    ]);

    echo "Admin account updated successfully.";
} else {
    $insert = $pdo->prepare(
        "INSERT INTO users
        (fullname, email, password, role)
        VALUES (?, ?, ?, 'admin')"
    );

    $insert->execute([
        "ScholarTrack Administrator",
        $email,
        $hashed_password
    ]);

    echo "Admin account created successfully.";
}
?>