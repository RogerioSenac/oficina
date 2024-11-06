<?php
function validarLogin($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = :username AND password = :password');
    $stmt->execute(['username' => $username, 'password' => md5($password)]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
